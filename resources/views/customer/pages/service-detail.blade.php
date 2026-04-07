@extends('customer.layouts.app')

@section('content')
<a href="{{ route('customer.catalog') }}" class="inline-flex items-center gap-1 text-gray-400 text-sm mb-4 hover:text-brand transition">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
    </svg>
    Volver al catálogo
</a>

@if ($service->image)
    <div class="aspect-[16/9] rounded-2xl overflow-hidden mb-5">
        <img src="{{ asset('storage/'.$service->image) }}" class="w-full h-full object-cover" alt="">
    </div>
@else
    <div class="aspect-[16/9] rounded-2xl mb-5 bg-gradient-to-br from-brand/20 via-purple-900/30 to-black/60 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" class="w-24 h-24 text-brand opacity-40">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
        </svg>
    </div>
@endif

<h1 class="text-2xl font-bold mb-2 tracking-tight">{{ $service->name }}</h1>
<div class="flex items-baseline gap-3 mb-4">
    <span class="text-4xl font-extrabold bg-gradient-to-r from-brand to-emerald-300 bg-clip-text text-transparent">${{ number_format($service->price, 0) }}</span>
    <span class="inline-flex items-center gap-1 text-sm text-gray-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        {{ $service->duration_min }} min
    </span>
</div>

<div class="flex gap-2 mb-5 text-xs flex-wrap">
    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-brand/10 border border-brand/30 text-brand font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
        </svg>
        +{{ $service->stamps_earned }} sello{{ $service->stamps_earned === 1 ? '' : 's' }}
    </span>
    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 font-semibold">
        +{{ $service->points_earned }} puntos
    </span>
</div>

@if ($service->description)
    <div class="glass rounded-2xl p-5 mb-5 text-sm text-gray-300 leading-relaxed">
        {!! \Illuminate\Support\Str::markdown($service->description) !!}
    </div>
@endif

{{-- Form de reserva --}}
<div class="glass rounded-2xl p-5 mb-5">
    <h2 class="font-bold mb-4 text-lg">Reservar</h2>
    <form action="{{ route('customer.service.book', $service) }}" method="POST" class="space-y-4" x-data="{ type: 'in_shop' }">
        @csrf

        <div class="grid grid-cols-2 gap-2">
            <label class="cursor-pointer">
                <input type="radio" name="type" value="in_shop" x-model="type" class="sr-only peer">
                <div class="p-4 rounded-xl border border-white/10 text-center text-sm peer-checked:border-brand peer-checked:bg-brand/10 peer-checked:text-brand transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto mb-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/>
                    </svg>
                    En el lavado
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="type" value="at_home" x-model="type" class="sr-only peer">
                <div class="p-4 rounded-xl border border-white/10 text-center text-sm peer-checked:border-brand peer-checked:bg-brand/10 peer-checked:text-brand transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto mb-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    A domicilio
                </div>
            </label>
        </div>

        <div x-show="type === 'at_home'" x-cloak>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Dirección</label>
            <input type="text" name="address" placeholder="Calle, número, colonia"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Fecha y hora</label>
            <input type="datetime-local" name="scheduled_at"
                   min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                   value="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
                   required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Notas (opcional)</label>
            <textarea name="notes" rows="2" placeholder="Marca, modelo, color, observaciones..."
                      class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand"></textarea>
        </div>

        @error('scheduled_at') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
        @error('address') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror

        <button type="submit" class="w-full font-extrabold py-4 rounded-xl text-lg active:scale-95 transition shadow-[0_8px_30px_rgba(6,182,212,0.5)]"
                style="background:linear-gradient(135deg,#06b6d4,#10b981);color:#0a0a0a;">
            Reservar ahora
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak]{display:none}</style>
<div class="h-20"></div>
@endsection
