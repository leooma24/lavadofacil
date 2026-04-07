@extends('customer.layouts.app')

@section('content')
<header class="mb-6">
    <h1 class="text-3xl font-bold">Mis citas</h1>
    @if (session('booking_success'))
        <div class="mt-3 glass rounded-2xl p-4 border-brand/40 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-brand shrink-0">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
            </svg>
            <p class="text-brand font-semibold text-sm">Cita reservada. Te avisaremos cuando esté lista.</p>
        </div>
    @endif
</header>

@forelse ($appointments as $apt)
    @php
        $color = match ($apt->status) {
            'pending' => 'border-gray-500/40',
            'confirmed' => 'border-blue-500/40',
            'in_queue', 'in_progress' => 'border-yellow-500/40',
            'ready' => 'border-brand glow',
            'completed' => 'border-green-500/30 opacity-60',
            'cancelled' => 'border-red-500/30 opacity-50',
            default => 'border-white/10',
        };
    @endphp
    <div class="glass rounded-2xl p-5 mb-3 {{ $color }}">
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1 min-w-0">
                <p class="font-bold truncate">{{ $apt->service?->name ?? 'Servicio' }}</p>
                <div class="flex items-center gap-1.5 text-xs text-gray-400 mt-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                    <span>{{ $apt->scheduled_at->translatedFormat('d M Y · H:i') }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-gray-400 mt-1">
                    @if ($apt->type === 'at_home')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span>A domicilio @if ($apt->address)— {{ $apt->address }} @endif</span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/>
                        </svg>
                        <span>En el lavado</span>
                    @endif
                </div>
            </div>
            <span class="text-xs px-3 py-1.5 rounded-full bg-white/5 border border-white/10 shrink-0 ml-3 font-semibold">
                {{ $apt->statusLabel() }}
            </span>
        </div>
    </div>
@empty
    <div class="glass rounded-2xl p-10 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-brand/10 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-brand">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
            </svg>
        </div>
        <p class="text-gray-400 mb-4">Aún no tienes citas reservadas</p>
        <a href="{{ route('customer.catalog') }}" class="inline-block font-extrabold px-6 py-3 rounded-xl active:scale-95 transition shadow-lg shadow-brand/30"
           style="background:linear-gradient(135deg,#06b6d4,#10b981);color:#0a0a0a;">
            Ver catálogo
        </a>
    </div>
@endforelse

<div class="h-20"></div>
@endsection
