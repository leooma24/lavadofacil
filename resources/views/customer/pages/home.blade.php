@extends('customer.layouts.app')

@section('content')
{{-- Header saludo --}}
<header class="mb-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ now()->translatedFormat('l, d M') }}</p>
            <h1 class="text-3xl font-bold mt-1">
                Hola, <span class="text-brand">{{ explode(' ', $customer->name)[0] }}</span>
            </h1>
            @if ($stamps > 0 && $stampsLeft > 0)
                <p class="text-gray-400 text-sm mt-1">Te faltan <span class="text-brand font-semibold">{{ $stampsLeft }} sello{{ $stampsLeft === 1 ? '' : 's' }}</span> para tu premio</p>
            @elseif ($stampsLeft === 0 && $stamps === 0)
                <p class="text-gray-400 text-sm mt-1">¡Comienza tu primera tarjeta!</p>
            @endif
        </div>
        <form action="{{ route('customer.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-10 h-10 rounded-full glass flex items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                </svg>
            </button>
        </form>
    </div>
</header>

@if (session('survey_thanks'))
<div class="glass rounded-2xl p-4 mb-5 border-brand/50 text-center">
    <p class="text-brand font-semibold">¡Gracias por tu opinión!</p>
</div>
@endif

{{-- Banner: cita lista, esperando respuesta del cliente --}}
@if ($readyAppointment)
<div class="rounded-2xl p-5 mb-5 bg-gradient-to-br from-brand/30 to-emerald-900/50 border border-brand pulse-glow">
    <div class="flex items-center gap-3 mb-3">
        <div class="w-12 h-12 rounded-2xl bg-brand/20 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6 text-brand">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
            </svg>
        </div>
        <div>
            <p class="font-bold">¡Tu auto te está esperando!</p>
            <p class="text-xs text-gray-300">El lavado dice que ya puedes traerlo</p>
        </div>
    </div>
    <p class="text-sm text-gray-200 mb-4">{{ $readyAppointment->service?->name }}</p>
    <div class="grid grid-cols-2 gap-2">
        <form action="{{ route('customer.appointment.respond', $readyAppointment) }}" method="POST">
            @csrf
            <input type="hidden" name="response" value="going">
            <button type="submit" class="w-full font-extrabold py-3 rounded-xl active:scale-95" style="background:linear-gradient(135deg,#06b6d4,#10b981);color:#0a0a0a;">
                Voy en camino
            </button>
        </form>
        <form action="{{ route('customer.appointment.respond', $readyAppointment) }}" method="POST">
            @csrf
            <input type="hidden" name="response" value="cant_make_it">
            <button type="submit" class="w-full glass border-red-500/30 text-red-300 font-semibold py-3 rounded-xl active:scale-95">
                No puedo
            </button>
        </form>
    </div>
</div>
@endif

{{-- Banner encuesta pendiente --}}
@if ($pendingSurveyVisit)
<a href="{{ route('customer.survey.show', $pendingSurveyVisit) }}" class="block glass rounded-2xl p-4 mb-5 border-yellow-500/40">
    <div class="flex items-center gap-3">
        <div class="w-11 h-11 rounded-2xl bg-yellow-400/15 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-300">
                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007Z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-semibold text-yellow-300">¿Cómo estuvo tu última visita?</p>
            <p class="text-xs text-gray-400">Tap para calificar</p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-yellow-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
        </svg>
    </div>
</a>
@endif

{{-- Tarjeta de fidelidad --}}
<section class="glass rounded-3xl p-6 mb-5 glow">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xs font-bold uppercase tracking-wider text-brand">Tarjeta Fidelidad</h2>
        <div class="text-right">
            <p class="text-[10px] text-gray-500 uppercase">Puntos</p>
            <p class="text-xl font-bold">{{ number_format($customer->points_balance) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-3 mb-5">
        @for ($i = 1; $i <= $stampsTotal; $i++)
            <div class="aspect-square rounded-2xl flex items-center justify-center {{ $i <= $stamps ? 'stamp-filled' : 'stamp-empty' }}">
                @if ($i <= $stamps)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="w-7 h-7">
                        <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>
        @endfor
    </div>

    <div class="h-2 bg-white/5 rounded-full overflow-hidden mb-3">
        <div class="h-full bg-gradient-to-r from-brand to-emerald-300 rounded-full transition-all" style="width: {{ ($stamps / $stampsTotal) * 100 }}%"></div>
    </div>

    <div class="flex items-center justify-between text-xs">
        <span class="text-gray-500">{{ $stamps }} / {{ $stampsTotal }} lavados</span>
        <span class="text-gray-400">Premio: <span class="text-brand font-semibold">1 lavado gratis</span></span>
    </div>
</section>

{{-- Stats del mes --}}
<h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 px-1">Este mes</h2>
<div class="grid grid-cols-2 gap-3 mb-6">
    <div class="glass rounded-2xl p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Visitas</p>
        <p class="text-3xl font-bold text-brand mt-1">{{ $visitsMonth }}</p>
    </div>
    <div class="glass rounded-2xl p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Gastado</p>
        <p class="text-3xl font-bold mt-1">${{ number_format($spentMonth, 0) }}</p>
    </div>
    <div class="glass rounded-2xl p-5">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Boletos rifa</p>
        <p class="text-3xl font-bold text-yellow-400 mt-1">{{ $raffleTickets }}</p>
    </div>
    <a href="{{ route('customer.ranking') }}" class="glass rounded-2xl p-5 active:scale-95 transition">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Mi nivel</p>
        <div class="flex items-center gap-2 mt-1">
            @include('customer.partials.level-icon', ['level' => $customer->level, 'class' => 'w-6 h-6'])
            <span class="text-lg font-bold capitalize">{{ $customer->level }}</span>
        </div>
        <p class="text-[10px] text-brand mt-1">Ver ranking →</p>
    </a>
</div>

{{-- Rifa del mes --}}
@if ($raffle)
<section class="glass rounded-3xl p-5 mb-5 border-yellow-500/30">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-yellow-500/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6 text-yellow-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold">{{ $raffle->name }}</h3>
                <p class="text-xs text-gray-400">Sorteo: {{ $raffle->draw_date->translatedFormat('d M') }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-[10px] text-gray-500 uppercase">Tus tickets</p>
            <p class="text-2xl font-bold text-yellow-400">{{ $raffleTickets }}</p>
        </div>
    </div>
    <p class="text-sm text-gray-300 mt-2">{{ $raffle->prize_description }}</p>
</section>
@endif

{{-- Botón de notificaciones --}}
<button id="enable-push" class="hidden w-full glass rounded-2xl p-4 mb-3 text-sm text-gray-300 active:scale-95 transition">
    <span class="inline-flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 text-brand">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
        </svg>
        Activar notificaciones
    </span>
</button>

<div class="h-20"></div>

{{-- ═══════ MODAL DE LA RULETA ═══════ --}}
@if ($unclaimedSpin)
<div id="prize-modal" class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex items-center justify-center p-6">
    {{-- Confetti background glow --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 rounded-full bg-brand/20 blur-[120px]"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 rounded-full bg-purple-500/15 blur-[120px]"></div>
    </div>

    <div class="max-w-sm w-full text-center relative">
        <div class="mb-8">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass border-brand/40 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-brand">
                    <path fill-rule="evenodd" d="M9 4.5a.75.75 0 0 1 .721.544l.813 2.846a3.75 3.75 0 0 0 2.576 2.576l2.846.813a.75.75 0 0 1 0 1.442l-2.846.813a3.75 3.75 0 0 0-2.576 2.576l-.813 2.846a.75.75 0 0 1-1.442 0l-.813-2.846a3.75 3.75 0 0 0-2.576-2.576l-2.846-.813a.75.75 0 0 1 0-1.442l2.846-.813A3.75 3.75 0 0 0 7.466 7.89l.813-2.846A.75.75 0 0 1 9 4.5ZM18 1.5a.75.75 0 0 1 .728.568l.258 1.036a2.63 2.63 0 0 0 1.91 1.91l1.036.258a.75.75 0 0 1 0 1.456l-1.036.258a2.63 2.63 0 0 0-1.91 1.91l-.258 1.036a.75.75 0 0 1-1.456 0l-.258-1.036a2.625 2.625 0 0 0-1.91-1.91l-1.036-.258a.75.75 0 0 1 0-1.456l1.036-.258a2.625 2.625 0 0 0 1.91-1.91l.258-1.036A.75.75 0 0 1 18 1.5Z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest text-brand">Tarjeta completada</span>
            </div>
            <h2 class="text-3xl font-bold tracking-tight">Gira la ruleta</h2>
            <p class="text-gray-400 text-sm mt-2">Reclama tu premio</p>
        </div>

        {{-- Wheel --}}
        <div class="relative mx-auto w-80 h-80 mb-8">
            {{-- Outer glow ring --}}
            <div class="absolute -inset-3 rounded-full bg-gradient-to-br from-brand via-purple-500 to-pink-500 opacity-30 blur-xl animate-pulse"></div>

            {{-- Decorative dots around wheel --}}
            <div class="absolute inset-0 rounded-full" id="wheel-dots"></div>

            {{-- Pointer --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1 z-20">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 32" class="w-7 h-9 drop-shadow-lg">
                    <defs>
                        <linearGradient id="ptr" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0" stop-color="#fde047"/>
                            <stop offset="1" stop-color="#f59e0b"/>
                        </linearGradient>
                    </defs>
                    <path d="M12 30 L0 6 Q12 0 24 6 Z" fill="url(#ptr)" stroke="#0a0a0a" stroke-width="1.5"/>
                </svg>
            </div>

            {{-- Wheel disc with SVG slices for sharper look --}}
            <div id="wheel" class="absolute inset-0 rounded-full shadow-[0_20px_60px_-15px_rgba(6,182,212,0.5)]"
                 style="background: conic-gradient(
                     #06b6d4 0deg 60deg,
                     #8b5cf6 60deg 120deg,
                     #ec4899 120deg 180deg,
                     #f59e0b 180deg 240deg,
                     #10b981 240deg 300deg,
                     #ef4444 300deg 360deg
                 ); border: 8px solid #0a0a0a; box-shadow: inset 0 0 0 4px rgba(255,255,255,0.1), 0 0 60px rgba(6,182,212,0.3);">
                {{-- Divider lines --}}
                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 200 200">
                    @for ($i = 0; $i < 6; $i++)
                        <line x1="100" y1="100"
                              x2="{{ 100 + 100 * cos(deg2rad($i * 60 - 90)) }}"
                              y2="{{ 100 + 100 * sin(deg2rad($i * 60 - 90)) }}"
                              stroke="rgba(0,0,0,0.4)" stroke-width="1.5"/>
                    @endfor
                </svg>
            </div>

            {{-- Center hub --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-900 to-black border-[5px] border-brand flex items-center justify-center shadow-[0_0_30px_rgba(6,182,212,0.6)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-9 h-9 text-brand">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Result --}}
        <div id="prize-result" class="hidden mb-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-yellow-400/20 border border-yellow-400/50 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-300">
                    <path fill-rule="evenodd" d="M12 1.5a.75.75 0 0 1 .75.75V4.5a.75.75 0 0 1-1.5 0V2.25A.75.75 0 0 1 12 1.5ZM5.636 4.136a.75.75 0 0 1 1.06 0l1.592 1.591a.75.75 0 0 1-1.061 1.06l-1.591-1.59a.75.75 0 0 1 0-1.061Zm12.728 0a.75.75 0 0 1 0 1.06l-1.591 1.592a.75.75 0 0 1-1.06-1.061l1.59-1.591a.75.75 0 0 1 1.061 0ZM12 6a3 3 0 0 0-3 3 .75.75 0 1 1-1.5 0 4.5 4.5 0 1 1 7.5 3.366l-.793.793a.75.75 0 0 0-.207.53V14a.75.75 0 0 1-1.5 0v-.311c0-.464.184-.909.513-1.237l.793-.793A3 3 0 0 0 12 6ZM12 18.75a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H12a.75.75 0 0 1-.75-.75v-.008Z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs font-bold uppercase tracking-widest text-yellow-300">¡Ganaste!</span>
            </div>
            <h3 class="text-4xl font-extrabold text-white tracking-tight">{{ $unclaimedSpin->prize->name }}</h3>
            @if ($unclaimedSpin->prize->description)
                <p class="text-sm text-gray-300 mt-3">{{ $unclaimedSpin->prize->description }}</p>
            @endif
            <p class="text-sm text-gray-300 mt-4 font-medium">Muéstralo al lavado para reclamarlo</p>
        </div>

        <button id="spin-btn" class="w-full font-extrabold py-4 rounded-2xl text-lg active:scale-95 transition shadow-[0_8px_30px_rgba(6,182,212,0.5)]"
                style="background: linear-gradient(135deg, #06b6d4, #10b981); color: #0a0a0a;">
            <span class="inline-flex items-center justify-center gap-2" style="color: #0a0a0a;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
                GIRAR LA RULETA
            </span>
        </button>
        <button id="close-modal" class="hidden w-full mt-3 text-white font-semibold py-3 text-sm rounded-2xl border border-white/20 active:scale-95 transition">
            Cerrar
        </button>
    </div>
</div>

<style>
@keyframes wheel-dot {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
}
.wheel-dot {
    animation: wheel-dot 1.5s ease-in-out infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wheel = document.getElementById('wheel');
    const spinBtn = document.getElementById('spin-btn');
    const result = document.getElementById('prize-result');
    const closeBtn = document.getElementById('close-modal');
    const modal = document.getElementById('prize-modal');

    spinBtn.addEventListener('click', () => {
        const totalRotation = 1800 + Math.floor(Math.random() * 360);
        wheel.style.transition = 'transform 4s cubic-bezier(0.17, 0.67, 0.16, 0.99)';
        wheel.style.transform = `rotate(${totalRotation}deg)`;
        spinBtn.disabled = true;
        spinBtn.classList.add('opacity-50');

        setTimeout(() => {
            result.classList.remove('hidden');
            spinBtn.classList.add('hidden');
            closeBtn.classList.remove('hidden');
        }, 4200);
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
});
</script>
@endif

{{-- Push notifications setup --}}
<script>
(async () => {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    const btn = document.getElementById('enable-push');
    const reg = await navigator.serviceWorker.ready;
    const existing = await reg.pushManager.getSubscription();
    if (existing) return; // ya suscrito

    btn.classList.remove('hidden');
    btn.addEventListener('click', async () => {
        try {
            const perm = await Notification.requestPermission();
            if (perm !== 'granted') return;

            const res = await fetch('{{ route("customer.push.key") }}');
            const { key } = await res.json();
            if (!key) { alert('Las notificaciones aún no están configuradas'); return; }

            const sub = await reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(key),
            });

            await fetch('{{ route("customer.push.subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(sub),
            });

            btn.classList.add('hidden');
        } catch (e) { console.error(e); }
    });

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const raw = atob(base64);
        return Uint8Array.from([...raw].map(c => c.charCodeAt(0)));
    }
})();
</script>
@endsection
