<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="{{ tenant()->primary_color ?? '#10b981' }}">
    <title>{{ tenant()->name }}</title>

    <link rel="manifest" href="{{ url('/manifest.webmanifest') }}">
    <link rel="icon" href="{{ asset('images/lavadofacil_icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/lavadofacil_icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root { --brand: {{ tenant()->primary_color ?? '#10b981' }}; }
        html, body { background: #0a0a0a; color: #fafafa; font-family: 'Inter', system-ui, sans-serif; -webkit-tap-highlight-color: transparent; overflow-x: hidden; max-width: 100vw; }
        /* Mesh + grid decorativo fijo detrás del contenido */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: -2; pointer-events: none;
            background:
                radial-gradient(circle at 20% 0%, rgba(14,165,233,0.18), transparent 50%),
                radial-gradient(circle at 80% 30%, rgba(6,182,212,0.15), transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(59,130,246,0.12), transparent 50%);
        }
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: -1; pointer-events: none; opacity: 0.35;
            background-image:
                linear-gradient(rgba(14,165,233,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(14,165,233,0.08) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .text-brand { color: var(--brand); }
        .bg-brand { background-color: var(--brand); }
        .border-brand { border-color: var(--brand); }
        .focus\:border-brand:focus { border-color: var(--brand); }
        .from-brand { --tw-gradient-from: var(--brand); --tw-gradient-to: rgb(0 0 0 / 0); --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to); }
        .shadow-brand\/30 { --tw-shadow-color: color-mix(in srgb, var(--brand) 30%, transparent); --tw-shadow: var(--tw-shadow-colored); }
        .glow { box-shadow: 0 0 20px color-mix(in srgb, var(--brand) 15%, transparent); }
        .glass {
            background: linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .stamp-filled {
            background: radial-gradient(circle at 30% 30%, var(--brand), #047857);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--brand) 40%, transparent), inset 0 1px 0 rgba(255,255,255,0.3);
        }
        .stamp-empty {
            background: rgba(255,255,255,0.03);
            border: 2px dashed rgba(255,255,255,0.15);
        }
        @keyframes pulse-glow { 0%,100% { box-shadow: 0 0 20px color-mix(in srgb, var(--brand) 30%, transparent); } 50% { box-shadow: 0 0 30px color-mix(in srgb, var(--brand) 60%, transparent); } }
        .pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
        .safe-bottom { padding-bottom: max(env(safe-area-inset-bottom), 1rem); }
        @keyframes spin-wheel { 0% { transform: rotate(0); } 100% { transform: rotate(var(--spin-end, 1800deg)); } }
        .wheel-spin { animation: spin-wheel 4s cubic-bezier(0.17, 0.67, 0.16, 0.99) forwards; }
    </style>
</head>
<body class="font-sans min-h-screen pb-24">
    <main class="max-w-md mx-auto px-5 pt-8">
        @yield('content')
    </main>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>

    @auth('customer')
    <nav class="fixed bottom-0 inset-x-0 z-50 bg-black/80 backdrop-blur-xl border-t border-white/10 safe-bottom">
        <div class="max-w-md mx-auto grid grid-cols-5 px-2 pt-3">
            @php
                $items = [
                    ['route' => 'customer.home',     'icon' => 'home',     'label' => 'Inicio'],
                    ['route' => 'customer.catalog',  'icon' => 'sparkles', 'label' => 'Servicios'],
                    ['route' => 'customer.appointments', 'icon' => 'calendar', 'label' => 'Citas'],
                    ['route' => 'customer.prizes',   'icon' => 'gift',     'label' => 'Premios'],
                    ['route' => 'customer.profile',  'icon' => 'user',     'label' => 'Perfil'],
                ];
                $icons = [
                    'home'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12 12 2.25 21.75 12M4.5 9.75v10.125a1.125 1.125 0 0 0 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125a1.125 1.125 0 0 0 1.125-1.125V9.75"/>',
                    'sparkles' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"/>',
                    'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>',
                    'gift'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>',
                    'user'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>',
                ];
            @endphp
            @foreach ($items as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-1 py-2 transition {{ $active ? 'text-brand' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="{{ $active ? '2' : '1.5' }}" stroke="currentColor" class="w-6 h-6">{!! $icons[$item['icon']] !!}</svg>
                    <span class="text-[10px] font-medium uppercase tracking-wide">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>
    @endauth
</body>
</html>
