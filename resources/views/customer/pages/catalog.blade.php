@extends('customer.layouts.app')

@section('content')
<header class="mb-6">
    <h1 class="text-3xl font-bold">Servicios</h1>
    <p class="text-gray-400 text-sm mt-1">Reserva el que prefieras</p>
</header>

<div class="space-y-3">
    @forelse ($services as $service)
        <a href="{{ route('customer.service.show', $service) }}" class="block glass rounded-2xl overflow-hidden active:scale-[0.98] transition">
            @if ($service->image)
                <div class="aspect-[16/9] bg-gradient-to-br from-brand/20 to-black/40 relative">
                    <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" class="absolute inset-0 w-full h-full object-cover">
                </div>
            @else
                <div class="aspect-[16/9] bg-gradient-to-br from-brand/20 via-purple-900/30 to-black/60 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="w-20 h-20 text-brand opacity-40">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                </div>
            @endif
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="font-bold text-lg leading-tight">{{ $service->name }}</h3>
                    <p class="text-2xl font-bold text-brand shrink-0 ml-3">${{ number_format($service->price, 0) }}</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        {{ $service->duration_min }} min
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-brand/10 border border-brand/30 text-brand font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                        </svg>
                        +{{ $service->stamps_earned }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-yellow-400/10 border border-yellow-400/30 text-yellow-400 font-semibold">
                        +{{ $service->points_earned }} pts
                    </span>
                </div>
            </div>
        </a>
    @empty
        <div class="glass rounded-2xl p-10 text-center text-gray-500">No hay servicios disponibles</div>
    @endforelse
</div>

<div class="h-20"></div>
@endsection
