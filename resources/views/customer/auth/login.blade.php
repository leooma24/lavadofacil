@extends('customer.layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl glass glow mb-5 pulse-glow">
            <img src="{{ asset('images/lavadofacil_icon.png') }}" alt="" class="w-12 h-12">
        </div>
        <h1 class="text-3xl font-bold tracking-tight">{{ tenant()->name }}</h1>
        <p class="text-gray-400 text-sm mt-2">Programa de fidelización</p>
    </div>

    <form method="POST" action="{{ route('customer.login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Tu teléfono</label>
            <input type="tel"
                   name="phone"
                   inputmode="numeric"
                   maxlength="10"
                   placeholder="6681234567"
                   value="{{ old('phone') }}"
                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-lg font-medium focus:outline-none focus:border-brand focus:bg-white/10 transition"
                   required autofocus>
            @error('phone')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">PIN de 4 dígitos</label>
            <input type="password"
                   name="pin"
                   inputmode="numeric"
                   maxlength="4"
                   placeholder="••••"
                   class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-2xl font-bold tracking-[1em] text-center focus:outline-none focus:border-brand focus:bg-white/10 transition"
                   required>
            @error('pin')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-xs mt-2 text-center">Por defecto son los últimos 4 dígitos de tu teléfono</p>
        </div>

        <button type="submit"
                class="w-full bg-brand text-black font-bold py-4 rounded-2xl text-lg active:scale-95 transition shadow-lg shadow-brand/30">
            Entrar
        </button>
    </form>

    <p class="text-center text-gray-600 text-xs mt-10">
        ¿No estás registrado? Acércate al lavado y pídelo.
    </p>
</div>
@endsection
