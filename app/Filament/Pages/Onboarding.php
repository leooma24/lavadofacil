<?php

namespace App\Filament\Pages;

use App\Models\Tenant\MessageTemplate;
use App\Models\Tenant\Prize;
use App\Models\Tenant\Service;
use App\Services\Features;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class Onboarding extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $title = 'Configuración inicial';
    protected static ?string $navigationLabel = 'Onboarding';
    protected static string $view = 'filament.pages.onboarding';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $tenant = tenant();

        if ($tenant && $tenant->onboarding_completed_at) {
            redirect(\Filament\Facades\Filament::getUrl());
            return;
        }

        // Pre-cargar features del plan (todas en ON por default para que el dueño decida qué apagar)
        $available = Features::availableForCurrentTenant();
        $featuresState = [];
        foreach ($available as $key => $meta) {
            $featuresState[$key] = true;
        }

        $this->form->fill([
            'business_name' => $tenant?->name,
            'primary_color' => $tenant?->primary_color ?? '#06b6d4',
            'features' => $featuresState,
            'services' => Service::orderBy('sort_order')->get()->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'price' => $s->price,
                'duration_min' => $s->duration_min,
                'is_active' => (bool) $s->is_active,
            ])->toArray(),
            'prizes' => Prize::orderBy('sort_order')->get()->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'probability_weight' => $p->probability_weight,
                'is_active' => (bool) $p->is_active,
            ])->toArray(),
            'templates' => MessageTemplate::where('channel', 'whatsapp')
                ->orderBy('type')
                ->get()
                ->map(fn ($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'body' => $t->body,
                    'is_active' => (bool) $t->is_active,
                ])->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        $available = Features::availableForCurrentTenant();

        // Agrupar toggles por categoría
        $byGroup = [];
        foreach ($available as $key => $meta) {
            $byGroup[$meta['group']][$key] = $meta;
        }
        $featureSections = [];
        foreach ($byGroup as $groupName => $items) {
            $toggles = [];
            foreach ($items as $key => $meta) {
                $toggles[] = Toggle::make("features.{$key}")
                    ->label($meta['label'])
                    ->helperText($meta['description'])
                    ->inline(false)
                    ->live();
            }
            $featureSections[] = Section::make($groupName)
                ->schema($toggles)
                ->columns(2)
                ->collapsible();
        }

        return $form
            ->schema([
                Wizard::make([
                    // ── PASO 1: Bienvenida ──────────────────────────────
                    Wizard\Step::make('Bienvenida')
                        ->icon('heroicon-o-hand-raised')
                        ->description('Datos del negocio')
                        ->schema([
                            Placeholder::make('intro')
                                ->label('')
                                ->content(new HtmlString(
                                    '<div class="text-lg font-semibold">¡Bienvenido a LavadoFácil!</div>'
                                    . '<p class="mt-2 text-sm opacity-80">Vamos a configurar tu negocio en unos minutos. Podrás cambiar todo después desde el panel.</p>'
                                )),
                            TextInput::make('business_name')
                                ->label('Nombre del negocio')
                                ->required(),
                            ColorPicker::make('primary_color')
                                ->label('Color principal')
                                ->helperText('Se usará en la PWA del cliente y los botones del panel'),
                        ]),

                    // ── PASO 2: Funciones que usará ─────────────────────
                    Wizard\Step::make('¿Qué funciones quieres usar?')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->description('Actívalas/desactívalas según tu negocio')
                        ->schema([
                            Placeholder::make('features_intro')
                                ->label('')
                                ->content('Marca solo las funciones que quieres usar al arrancar. Puedes activar las demás después desde Configuración → Funciones. Los siguientes pasos del wizard dependen de lo que marques aquí.'),
                            ...$featureSections,
                        ]),

                    // ── PASO 3: Servicios (siempre) ─────────────────────
                    Wizard\Step::make('Servicios')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->description('Tu catálogo')
                        ->schema([
                            Placeholder::make('services_intro')
                                ->label('')
                                ->content('Estos son los servicios sugeridos. Ajusta precios, desactiva los que no ofrezcas, o agrega nuevos desde el panel después.'),
                            Repeater::make('services')
                                ->label('')
                                ->schema([
                                    Grid::make(4)->schema([
                                        TextInput::make('name')->label('Nombre')->required()->columnSpan(2),
                                        TextInput::make('price')->label('Precio $')->numeric()->required(),
                                        TextInput::make('duration_min')->label('Min.')->numeric()->required(),
                                    ]),
                                    Toggle::make('is_active')->label('Activo')->default(true),
                                ])
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                ->collapsible()
                                ->addActionLabel('Agregar servicio')
                                ->defaultItems(0),
                        ]),

                    // ── PASO 4: Premios (solo si loyalty_card o rewards_wheel) ─
                    Wizard\Step::make('Premios de la ruleta')
                        ->icon('heroicon-o-gift')
                        ->description('Tarjeta de 8 sellos')
                        ->visible(fn (Get $get) => ($get('features.loyalty_card') || $get('features.rewards_wheel')))
                        ->schema([
                            Placeholder::make('prizes_intro')
                                ->label('')
                                ->content('Cuando un cliente completa 8 sellos gira la ruleta. El peso de probabilidad controla qué tan seguido sale cada premio (mayor = más común).'),
                            Repeater::make('prizes')
                                ->label('')
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('name')->label('Premio')->required()->columnSpan(2),
                                        TextInput::make('probability_weight')->label('Probabilidad')->numeric()->required(),
                                    ]),
                                    Toggle::make('is_active')->label('Activo')->default(true),
                                ])
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                ->collapsible()
                                ->addActionLabel('Agregar premio')
                                ->defaultItems(0),
                        ]),

                    // ── PASO 5: Plantillas WhatsApp (solo si whatsapp ON) ──
                    Wizard\Step::make('Plantillas de WhatsApp')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->description('Mensajes pre-fabricados')
                        ->visible(fn (Get $get) => (bool) $get('features.whatsapp'))
                        ->schema([
                            Placeholder::make('templates_intro')
                                ->label('')
                                ->content(new HtmlString(
                                    'Estas plantillas se envían manualmente desde WhatsApp Web cuando hagas clic en el botón de WhatsApp junto a un cliente. '
                                    . 'Las variables <code>{nombre}</code>, <code>{racha}</code>, etc. se reemplazan automáticamente. '
                                    . 'Activa las que quieras usar o personaliza su texto.'
                                )),
                            Repeater::make('templates')
                                ->label('')
                                ->schema([
                                    TextInput::make('name')->label('Nombre interno')->disabled(),
                                    Textarea::make('body')->label('Mensaje')->rows(4)->required(),
                                    Toggle::make('is_active')->label('Activa')->default(true),
                                ])
                                ->itemLabel(fn (array $state): ?string => ($state['is_active'] ?? false ? '● ' : '○ ') . ($state['name'] ?? ''))
                                ->collapsed()
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false),
                        ]),

                    // ── PASO FINAL ──────────────────────────────────────
                    Wizard\Step::make('¡Listo!')
                        ->icon('heroicon-o-check-circle')
                        ->description('Terminar')
                        ->schema([
                            Placeholder::make('done')
                                ->label('')
                                ->content(new HtmlString(
                                    '<div class="text-lg font-semibold">Todo listo</div>'
                                    . '<p class="mt-2 text-sm opacity-80">Tu negocio ya está configurado. Al hacer clic en <strong>Terminar</strong> serás llevado al escritorio.</p>'
                                )),
                        ]),
                ])
                ->submitAction(new HtmlString('<button type="submit" class="fi-btn fi-btn-color-primary px-6 py-3 rounded-lg">Terminar</button>')),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = $this->form->getState();
        $tenant = tenant();

        if ($tenant) {
            $tenant->forceFill([
                'name' => $state['business_name'] ?? $tenant->name,
                'primary_color' => $state['primary_color'] ?? $tenant->primary_color,
                'enabled_features' => $state['features'] ?? [],
                'onboarding_completed_at' => now(),
            ])->save();
        }

        foreach ($state['services'] ?? [] as $row) {
            if (! empty($row['id'])) {
                Service::where('id', $row['id'])->update([
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'duration_min' => $row['duration_min'],
                    'is_active' => $row['is_active'] ?? false,
                ]);
            } elseif (! empty($row['name'])) {
                Service::create([
                    'name' => $row['name'],
                    'price' => $row['price'] ?? 0,
                    'duration_min' => $row['duration_min'] ?? 15,
                    'points_earned' => 10,
                    'stamps_earned' => 1,
                    'is_active' => $row['is_active'] ?? true,
                ]);
            }
        }

        if (($state['features']['loyalty_card'] ?? false) || ($state['features']['rewards_wheel'] ?? false)) {
            foreach ($state['prizes'] ?? [] as $row) {
                if (! empty($row['id'])) {
                    Prize::where('id', $row['id'])->update([
                        'name' => $row['name'],
                        'probability_weight' => $row['probability_weight'],
                        'is_active' => $row['is_active'] ?? false,
                    ]);
                } elseif (! empty($row['name'])) {
                    Prize::create([
                        'name' => $row['name'],
                        'type' => 'discount_pct',
                        'value' => 0,
                        'probability_weight' => $row['probability_weight'] ?? 10,
                        'is_active' => $row['is_active'] ?? true,
                    ]);
                }
            }
        }

        if ($state['features']['whatsapp'] ?? false) {
            foreach ($state['templates'] ?? [] as $row) {
                if (! empty($row['id'])) {
                    MessageTemplate::where('id', $row['id'])->update([
                        'body' => $row['body'],
                        'is_active' => $row['is_active'] ?? false,
                    ]);
                }
            }
        }

        Notification::make()
            ->title('¡Onboarding completado!')
            ->body('Tu negocio ya está listo para operar.')
            ->success()
            ->send();

        redirect(\Filament\Facades\Filament::getUrl());
    }
}
