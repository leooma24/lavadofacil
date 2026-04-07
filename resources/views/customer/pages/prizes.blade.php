@extends('customer.layouts.app')

@section('content')
<header class="mb-6">
    <h1 class="text-3xl font-bold">Mis premios</h1>
    <p class="text-gray-400 text-sm mt-1">Premios ganados al completar tus tarjetas</p>
</header>

@forelse ($spins as $spin)
<div class="glass rounded-2xl p-5 mb-3 {{ $spin->claimed_at ? 'opacity-60' : '' }}">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shrink-0 shadow-lg shadow-yellow-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold truncate">{{ $spin->prize->name }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $spin->spun_at->translatedFormat('d M Y') }}</p>
        </div>
        @if ($spin->claimed_at)
            <span class="text-xs px-3 py-1.5 rounded-full bg-gray-700 text-gray-300 font-semibold">Reclamado</span>
        @else
            <span class="text-xs px-3 py-1.5 rounded-full bg-brand/20 border border-brand/40 text-brand font-bold">Disponible</span>
        @endif
    </div>
</div>
@empty
<div class="glass rounded-2xl p-10 text-center">
    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-yellow-400/20 to-orange-500/20 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-yellow-400">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
        </svg>
    </div>
    <p class="text-gray-400">Completa tu tarjeta de 8 sellos para ganar tu primer premio</p>
</div>
@endforelse

<div class="h-20"></div>
@endsection
