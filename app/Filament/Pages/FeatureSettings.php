<?php

namespace App\Filament\Pages;

use App\Models\Tenant;
use App\Services\FeatureConfigChecker;
use App\Services\Features;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class FeatureSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $navigationLabel = 'Funciones';
    protected static ?string $title = 'Funciones activas';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.feature-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $tenant = tenant();
        $enabled = $tenant?->enabled_features ?? [];

        // Por cada feature disponible en el plan, leer su estado actual.
        $state = [];
        foreach (Features::availableForCurrentTenant() as $key => $meta) {
            $state[$key] = $enabled[$key] ?? true;
        }

        $this->form->fill($state);
    }

    public function form(Form $form): Form
    {
        $available = Features::availableForCurrentTenant();

        // Agrupar por categoría
        $byGroup = [];
        foreach ($available as $key => $meta) {
            $byGroup[$meta['group']][$key] = $meta;
        }

        $sections = [];
        foreach ($byGroup as $groupName => $items) {
            $toggles = [];
            foreach ($items as $key => $meta) {
                $toggles[] = Toggle::make($key)
                    ->label($meta['label'])
                    ->helperText($meta['description'])
                    ->inline(false);
            }
            $sections[] = Section::make($groupName)
                ->schema($toggles)
                ->columns(2);
        }

        return $form
            ->schema($sections)
            ->statePath('data');
    }

    public function save(): void
    {
        $tenant = tenant();
        if (! $tenant instanceof Tenant) {
            return;
        }

        $previous = $tenant->enabled_features ?? [];
        $next = $this->form->getState();

        // Guardar en la BD central. El modelo Tenant usa la conexión central.
        $tenant->enabled_features = $next;
        $tenant->save();

        Notification::make()
            ->title('Funciones actualizadas')
            ->body('Recarga la página para ver los cambios en el menú.')
            ->success()
            ->send();

        // Detectar features recién activadas y sin configuración
        $newlyEnabled = [];
        foreach ($next as $key => $value) {
            $wasOn = (bool) ($previous[$key] ?? false);
            $isOn = (bool) $value;
            if (! $wasOn && $isOn) {
                $newlyEnabled[] = $key;
            }
        }

        $catalog = Features::CATALOG;
        foreach ($newlyEnabled as $feature) {
            if (FeatureConfigChecker::isConfigured($feature)) {
                continue;
            }

            $label = $catalog[$feature]['label'] ?? $feature;
            $url = FeatureConfigChecker::configureUrl($feature);

            $notification = Notification::make()
                ->title("Configurar «{$label}»")
                ->body("Acabas de activar esta función pero aún no tiene configuración. ¿Quieres configurarla ahora?")
                ->warning()
                ->persistent();

            if ($url) {
                $notification->actions([
                    NotificationAction::make('configure')
                        ->label('Configurar ahora')
                        ->url($url)
                        ->button(),
                    NotificationAction::make('later')
                        ->label('Más tarde')
                        ->close(),
                ]);
            }

            $notification->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Guardar cambios')
                ->submit('save'),
        ];
    }
}
