<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} — LavadoFácil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-sky-50 to-indigo-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        @if($tenant->logo)
            <img src="{{ $tenant->logo }}" alt="{{ $tenant->name }}" class="h-20 mx-auto mb-4">
        @endif
        <h1 class="text-3xl font-bold mb-2" style="color: {{ $tenant->primary_color }}">
            {{ $tenant->name }}
        </h1>
        <p class="text-gray-600 mb-6">Programa de fidelización</p>
        <div class="space-y-3">
            <a href="/admin" class="block w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                Panel del Dueño
            </a>
            <p class="text-sm text-gray-500">PWA del cliente próximamente</p>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-100 text-xs text-gray-400">
            Powered by <strong>LavadoFácil</strong>
        </div>
    </div>
</body>
</html>
