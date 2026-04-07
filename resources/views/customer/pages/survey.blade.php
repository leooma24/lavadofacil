@extends('customer.layouts.app')

@section('content')
<header class="mb-8 text-center">
    <h1 class="text-3xl font-bold">¿Cómo estuvo?</h1>
    <p class="text-gray-400 text-sm mt-2">Tu visita del {{ $visit->visited_at->translatedFormat('d M Y') }}</p>
</header>

<form action="{{ route('customer.survey.store', $visit) }}" method="POST" class="space-y-6">
    @csrf

    {{-- Rating con estrellas --}}
    <div class="glass rounded-2xl p-6">
        <p class="text-center text-sm text-gray-400 mb-4">Califica de 1 a 5</p>
        <div class="flex justify-center gap-3" x-data="{ rating: 0 }">
            @for ($i = 1; $i <= 5; $i++)
                <button type="button"
                        @click="rating = {{ $i }}; document.getElementById('rating-input').value = {{ $i }}"
                        :class="rating >= {{ $i }} ? 'text-yellow-400 scale-110' : 'text-gray-700'"
                        class="text-5xl transition-all">★</button>
            @endfor
        </div>
        <input type="hidden" name="rating" id="rating-input" value="0" required>
    </div>

    {{-- NPS --}}
    <div class="glass rounded-2xl p-6">
        <p class="text-sm text-gray-400 mb-4">¿Qué tan probable es que nos recomiendes? (0-10)</p>
        <div class="grid grid-cols-11 gap-1.5">
            @for ($n = 0; $n <= 10; $n++)
                <label class="cursor-pointer">
                    <input type="radio" name="nps" value="{{ $n }}" class="sr-only peer">
                    <div class="aspect-square rounded-lg flex items-center justify-center text-sm font-semibold border border-white/10 peer-checked:bg-brand peer-checked:text-black peer-checked:border-brand transition">{{ $n }}</div>
                </label>
            @endfor
        </div>
    </div>

    {{-- Comentario --}}
    <div class="glass rounded-2xl p-6">
        <label class="text-sm text-gray-400 mb-2 block">Comentario (opcional)</label>
        <textarea name="comments" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-sm focus:outline-none focus:border-brand" placeholder="Cuéntanos cómo te fue..."></textarea>
    </div>

    <button type="submit" class="w-full bg-brand text-black font-bold py-4 rounded-2xl text-lg active:scale-95 transition shadow-lg shadow-brand/30">
        Enviar
    </button>
</form>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<div class="h-20"></div>
@endsection
