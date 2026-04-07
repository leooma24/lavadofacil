@extends('customer.layouts.app')

@section('content')
<header class="mb-6">
    <h1 class="text-3xl font-bold">Mis visitas</h1>
    <p class="text-gray-400 text-sm mt-1">{{ $customer->total_visits }} visitas en total · ${{ number_format($customer->total_spent, 0) }} gastado</p>
</header>

@forelse ($visits as $visit)
<div class="glass rounded-2xl p-5 mb-3">
    <div class="flex items-start justify-between mb-3">
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ $visit->visited_at->translatedFormat('d M Y · H:i') }}</p>
            <p class="text-2xl font-bold text-brand mt-1">${{ number_format($visit->total, 0) }}</p>
        </div>
        @if ($visit->earned_stamps > 0)
            <div class="flex items-center gap-1 px-3 py-1.5 rounded-full bg-brand/10 border border-brand/30">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-brand">
                    <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs font-semibold text-brand">+{{ $visit->earned_stamps }} sello{{ $visit->earned_stamps === 1 ? '' : 's' }}</span>
            </div>
        @endif
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach ($visit->services as $service)
            <span class="text-xs px-3 py-1 rounded-full bg-white/5 border border-white/10">{{ $service->name }}{{ $service->pivot->quantity > 1 ? " ×{$service->pivot->quantity}" : '' }}</span>
        @endforeach
    </div>
</div>
@empty
<div class="glass rounded-2xl p-10 text-center text-gray-500">
    Aún no tienes visitas registradas
</div>
@endforelse

<div class="mt-4">{{ $visits->links() }}</div>
<div class="h-20"></div>
@endsection
