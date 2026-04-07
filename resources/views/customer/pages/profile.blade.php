@extends('customer.layouts.app')

@section('content')
<header class="mb-6 text-center">
    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-brand to-emerald-700 text-3xl font-bold mb-4 shadow-lg shadow-brand/30">
        {{ strtoupper(substr($customer->name, 0, 1)) }}
    </div>
    <h1 class="text-2xl font-bold">{{ $customer->name }}</h1>
    <p class="text-gray-500 text-sm mt-1">{{ $customer->phone }}</p>

    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mt-3">
        @include('customer.partials.level-icon', ['level' => $customer->level, 'class' => 'w-5 h-5'])
        <span class="text-sm font-semibold capitalize">Nivel {{ $customer->level }}</span>
    </div>
</header>

<div class="grid grid-cols-3 gap-3 mb-4">
    <div class="glass rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold text-brand">{{ $customer->total_visits }}</p>
        <p class="text-[10px] text-gray-500 uppercase mt-1 font-semibold">Visitas</p>
    </div>
    <div class="glass rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold">{{ number_format($customer->points_balance) }}</p>
        <p class="text-[10px] text-gray-500 uppercase mt-1 font-semibold">Puntos</p>
    </div>
    <div class="glass rounded-2xl p-4 text-center">
        <p class="text-3xl font-bold text-orange-400">{{ $customer->current_streak }}</p>
        <p class="text-[10px] text-gray-500 uppercase mt-1 font-semibold inline-flex items-center gap-1 justify-center">
            Racha
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-orange-400">
                <path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z" clip-rule="evenodd"/>
            </svg>
        </p>
    </div>
</div>

<div class="glass rounded-2xl p-5 mb-4">
    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total gastado</p>
    <p class="text-3xl font-extrabold mt-1 bg-gradient-to-r from-brand to-emerald-300 bg-clip-text text-transparent">${{ number_format($customer->total_spent, 2) }}</p>
</div>

@if ($currentLevel)
<div class="glass rounded-2xl p-5 mb-3">
    <h3 class="text-xs font-bold uppercase tracking-wider text-brand mb-3">Beneficios de tu nivel</h3>
    @if ($currentLevel->multiplier > 1)
        <div class="flex items-center gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-brand">
                <path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm font-semibold">{{ $currentLevel->multiplier }}× puntos en cada visita</span>
        </div>
    @endif
    @if (is_array($currentLevel->perks))
        @foreach ($currentLevel->perks as $perk)
            <div class="flex items-start gap-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-brand mt-0.5 shrink-0">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-gray-300">{{ $perk }}</span>
            </div>
        @endforeach
    @endif
</div>
@endif

@if ($nextLevel)
@php
    $visitsNeeded = max(0, $nextLevel->min_visits - $customer->total_visits);
    $spentNeeded = max(0, $nextLevel->min_spent - $customer->total_spent);
@endphp
<div class="glass rounded-2xl p-5 mb-3 border-yellow-500/30">
    <div class="flex items-center gap-2 mb-3">
        @include('customer.partials.level-icon', ['level' => $nextLevel->level, 'class' => 'w-5 h-5'])
        <p class="text-xs font-bold uppercase tracking-wider text-yellow-400">Para subir a {{ ucfirst($nextLevel->level) }}</p>
    </div>
    @if ($visitsNeeded > 0)
        <p class="text-sm text-gray-300">• {{ $visitsNeeded }} visita{{ $visitsNeeded === 1 ? '' : 's' }} más</p>
    @endif
    @if ($spentNeeded > 0)
        <p class="text-sm text-gray-300">• ${{ number_format($spentNeeded, 0) }} más en gasto</p>
    @endif
    @if ($visitsNeeded === 0 && $spentNeeded === 0)
        <p class="text-sm text-brand font-semibold">¡Listo! Te promoveremos en tu próxima visita</p>
    @endif
</div>
@endif

@if ($customer->is_vip)
<div class="rounded-2xl p-5 mb-3 bg-gradient-to-r from-yellow-500/20 to-amber-500/20 border border-yellow-500/40">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-2xl bg-yellow-400/20 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-yellow-300">
                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.007Z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-yellow-300">Cliente VIP</p>
            @if ($customer->vip_until)
                <p class="text-xs text-yellow-200/70">Hasta {{ $customer->vip_until->translatedFormat('d M Y') }}</p>
            @endif
        </div>
    </div>
</div>
@endif

<form action="{{ route('customer.logout') }}" method="POST" class="mt-8">
    @csrf
    <button type="submit" class="w-full glass py-4 rounded-2xl text-red-400 font-semibold active:scale-95 transition inline-flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
        </svg>
        Cerrar sesión
    </button>
</form>

<div class="h-20"></div>
@endsection
