<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LavadoFácil — Software de fidelización para car washes</title>
    <meta name="description" content="Convierte clientes ocasionales en clientes leales. Tarjeta de sellos digital, ruleta de premios, rifas, niveles VIP, recordatorios WhatsApp y reportes. El SaaS para car washes más completo de México.">
    <meta name="theme-color" content="#0ea5e9">
    <link rel="icon" type="image/png" href="{{ asset('images/lavadofacil_icon.png') }}">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:title" content="LavadoFácil — Software de fidelización para car washes">
    <meta property="og:description" content="Tarjeta de sellos digital, ruleta de premios, rifas, niveles VIP, WhatsApp y reportes.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="LavadoFácil">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        @keyframes gradient { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
        @keyframes slideIn { from{opacity:0;transform:translateX(-30px)} to{opacity:1;transform:translateX(0)} }
        @keyframes pulse-glow { 0%,100%{box-shadow:0 0 20px rgba(14,165,233,0.3)} 50%{box-shadow:0 0 40px rgba(14,165,233,0.6)} }
        @keyframes blob { 0%,100%{border-radius:60% 40% 30% 70%/60% 30% 70% 40%} 50%{border-radius:30% 60% 70% 40%/50% 60% 30% 60%} }
        .animate-gradient { background-size:200% 200%; animation:gradient 8s ease infinite; }
        .animate-fade-up { animation:fadeUp 0.8s ease forwards; }
        .animate-slide-in { animation:slideIn 0.6s ease forwards; }
        .animate-pulse-glow { animation:pulse-glow 3s ease infinite; }
        .animate-blob { animation:blob 10s ease-in-out infinite; }
        .delay-100{animation-delay:.1s}.delay-200{animation-delay:.2s}.delay-300{animation-delay:.3s}.delay-400{animation-delay:.4s}
        [data-animate] { opacity:0; }
        [data-animate].visible { opacity:1; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased overflow-x-hidden">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-lg border-b border-gray-100/50 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('images/lavadofacil_logo.png') }}" alt="LavadoFácil" class="h-12 transition-transform hover:scale-105">
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#problema" class="text-sm text-gray-600 hover:text-sky-600 transition font-medium">Por qué</a>
                <a href="#features" class="text-sm text-gray-600 hover:text-sky-600 transition font-medium">Funciones</a>
                <a href="#pricing" class="text-sm text-gray-600 hover:text-sky-600 transition font-medium">Precios</a>
                <a href="#contacto" class="text-sm text-gray-600 hover:text-sky-600 transition font-medium">Contacto</a>
                <a href="{{ url('/lavadodemo/admin/login') }}" class="text-sm text-gray-600 hover:text-sky-600 transition font-medium">Iniciar sesión</a>
                <a href="#contacto" class="inline-flex items-center px-5 py-2.5 bg-sky-600 text-white text-sm font-semibold rounded-xl hover:bg-sky-700 transition-all hover:shadow-lg hover:shadow-sky-200 hover:-translate-y-0.5">
                    Solicitar demo
                </a>
            </div>
            <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden md:hidden px-4 pb-4 space-y-2 bg-white border-t">
            <a href="#features" class="block py-2 text-gray-600">Funciones</a>
            <a href="#pricing" class="block py-2 text-gray-600">Precios</a>
            <a href="#contacto" class="block py-2 text-gray-600">Contacto</a>
            <a href="{{ url('/lavadodemo/admin/login') }}" class="block py-2 text-gray-600">Iniciar sesión</a>
            <a href="#contacto" class="block py-2 px-4 bg-sky-600 text-white text-center rounded-lg">Solicitar demo</a>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative pt-32 pb-24 px-4 overflow-hidden">
        <div class="absolute top-20 -left-40 w-96 h-96 bg-sky-200/40 rounded-full blur-3xl animate-blob"></div>
        <div class="absolute top-40 -right-40 w-96 h-96 bg-cyan-200/30 rounded-full blur-3xl animate-blob" style="animation-delay:3s"></div>

        <div class="max-w-5xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-sky-50 text-sky-700 text-sm font-semibold rounded-full mb-8 animate-fade-up border border-sky-100">
                <span class="w-2 h-2 bg-sky-500 rounded-full animate-pulse"></span>
                Diseñado para car washes mexicanos
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight leading-[1.1] animate-fade-up delay-100">
                Convierte clientes ocasionales<br>
                <span class="bg-gradient-to-r from-sky-600 via-cyan-500 to-sky-600 bg-clip-text text-transparent animate-gradient">
                    en clientes de por vida
                </span>
            </h1>

            <p class="mt-8 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed animate-fade-up delay-200">
                Mientras tu competencia regala cupones que nadie usa,
                <strong class="text-gray-900">tú puedes tener un programa de fidelización completo</strong>:
                tarjeta de sellos digital, ruleta de premios, rifas, niveles VIP y WhatsApp automático.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up delay-300">
                <a href="#contacto" class="group w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sky-600 to-cyan-600 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-sky-300/50 transition-all hover:-translate-y-1 text-lg">
                    Solicita tu demo gratis
                    <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">&rarr;</span>
                </a>
                <a href="{{ url('/lavadodemo/admin/login') }}" class="w-full sm:w-auto px-8 py-4 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Ver demo en vivo
                </a>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-gray-500 animate-fade-up delay-400">
                <span class="flex items-center gap-1"><svg class="w-4 h-4 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Sin tarjeta de crédito</span>
                <span class="flex items-center gap-1"><svg class="w-4 h-4 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>30 días de prueba</span>
                <span class="flex items-center gap-1"><svg class="w-4 h-4 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Setup en 5 min</span>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12 bg-gray-900">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div data-animate class="animate-fade-up"><div class="text-3xl sm:text-4xl font-extrabold text-white">+45%</div><div class="text-sm text-gray-400 mt-1">Más visitas recurrentes</div></div>
            <div data-animate class="animate-fade-up delay-100"><div class="text-3xl sm:text-4xl font-extrabold text-white">3x</div><div class="text-sm text-gray-400 mt-1">Más referidos por cliente</div></div>
            <div data-animate class="animate-fade-up delay-200"><div class="text-3xl sm:text-4xl font-extrabold text-white">−60%</div><div class="text-sm text-gray-400 mt-1">Clientes dormidos</div></div>
            <div data-animate class="animate-fade-up delay-300"><div class="text-3xl sm:text-4xl font-extrabold text-sky-400">5 min</div><div class="text-sm text-gray-400 mt-1">Para empezar</div></div>
        </div>
    </section>

    {{-- Pain vs Solution --}}
    <section id="problema" class="py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-animate>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 animate-fade-up">¿Te suena familiar?</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-12 items-start">
                <div class="space-y-6" data-animate>
                    <div class="text-center mb-6"><span class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-full text-sm font-semibold border border-red-100">Sin LavadoFácil</span></div>
                    @php
                    $pains = [
                        ['title' => 'Tarjetas de cartón perdidas', 'desc' => 'El cliente la deja en el carro, se moja, se pierde, y nunca completa los sellos. Adiós fidelización.'],
                        ['title' => 'No sabes quiénes son tus mejores clientes', 'desc' => 'Sin historial, sin ranking. Tratas igual al que viene 3 veces al año que al que viene cada semana.'],
                        ['title' => 'Promos por WhatsApp manuales y tardadas', 'desc' => 'Mandas mensajes uno por uno, te falta tiempo, dejas a medias y los clientes se enfrían.'],
                        ['title' => 'No mides nada, decides a ciegas', 'desc' => 'No sabes cuántos clientes nuevos tuviste, cuáles dejaron de venir, ni qué servicios dejan más utilidad.'],
                    ];
                    @endphp
                    @foreach($pains as $i => $pain)
                    <div class="flex gap-4 p-5 bg-red-50/50 rounded-xl border border-red-100 animate-slide-in" style="animation-delay:{{ $i * 0.15 }}s">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div><h3 class="font-bold text-gray-900">{{ $pain['title'] }}</h3><p class="text-sm text-gray-600 mt-1">{{ $pain['desc'] }}</p></div>
                    </div>
                    @endforeach
                </div>
                <div class="space-y-6" data-animate>
                    <div class="text-center mb-6"><span class="inline-flex items-center gap-2 px-4 py-2 bg-sky-50 text-sky-700 rounded-full text-sm font-semibold border border-sky-100">Con LavadoFácil</span></div>
                    @php
                    $solutions = [
                        ['title' => 'Tarjeta de sellos digital con ruleta', 'desc' => 'Cada visita suma un sello. Al completar la tarjeta giran la ruleta y ganan un premio. Adicción garantizada.'],
                        ['title' => 'Niveles Bronce, Plata, Oro y Platino', 'desc' => 'Tus mejores clientes suben de nivel automático y reciben beneficios. Saben que valoras su lealtad.'],
                        ['title' => 'WhatsApp con plantillas listas', 'desc' => 'Cumpleaños, recuperación de dormidos, recordatorios — todo con mensaje pre-llenado. Un clic y listo.'],
                        ['title' => 'Reportes en tiempo real', 'desc' => 'Ranking del mes, ingresos por servicio, clientes dormidos, predicción de utilidades. Decide con datos.'],
                    ];
                    @endphp
                    @foreach($solutions as $i => $sol)
                    <div class="flex gap-4 p-5 bg-sky-50/50 rounded-xl border border-sky-100 animate-slide-in" style="animation-delay:{{ $i * 0.15 + 0.3 }}s">
                        <div class="flex-shrink-0 w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div><h3 class="font-bold text-gray-900">{{ $sol['title'] }}</h3><p class="text-sm text-gray-600 mt-1">{{ $sol['desc'] }}</p></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-animate>
                <span class="inline-flex items-center px-3 py-1 bg-sky-50 text-sky-700 text-xs font-semibold rounded-full mb-4">14 MÓDULOS INCLUIDOS</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Todo lo que tu car wash necesita</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Cada función fue diseñada con dueños de car wash mexicanos en mente</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6" data-animate>
                @php
                $features = [
                    ['icon' => '🎯', 'title' => 'Tarjeta de 8 sellos', 'desc' => 'Cada visita suma. Al completar, gira la ruleta y se gana un premio aleatorio con probabilidad ponderada.'],
                    ['icon' => '🎁', 'title' => 'Ruleta de premios', 'desc' => 'Configura premios con probabilidad personalizada: lavado gratis, descuentos, kit de aromatizantes...'],
                    ['icon' => '🎟️', 'title' => 'Rifa mensual', 'desc' => 'Cada visita = ticket. Un sorteo grande al mes que mantiene a los clientes regresando.'],
                    ['icon' => '👑', 'title' => 'Niveles VIP', 'desc' => 'Bronce, Plata, Oro, Platino. Suben automático según gasto. Beneficios y descuentos por nivel.'],
                    ['icon' => '🤝', 'title' => 'Programa de referidos', 'desc' => 'Código único por cliente. Quien refiere gana premio, quien llega gana descuento. Crecimiento orgánico.'],
                    ['icon' => '🎂', 'title' => 'Cumpleaños automático', 'desc' => 'WhatsApp + descuento el día del cumple del cliente. Sin que tengas que recordarlo.'],
                    ['icon' => '🔥', 'title' => 'Racha de visitas', 'desc' => 'Cuenta semanas consecutivas. Premios por mantener la racha. Adicción dulce.'],
                    ['icon' => '💬', 'title' => 'WhatsApp manual con plantillas', 'desc' => '20 plantillas listas. Un clic abre wa.me con el mensaje pre-llenado. Sin Meta, sin pagar API.'],
                    ['icon' => '😴', 'title' => 'Reactivación de dormidos', 'desc' => 'Lista de clientes que no han venido en 30+ días. Botón directo de WhatsApp para traerlos de vuelta.'],
                    ['icon' => '🏆', 'title' => 'Ranking del mes', 'desc' => 'Top clientes por visitas y por gasto. Premio al #1 de cada mes. Competencia sana entre clientes.'],
                    ['icon' => '⭐', 'title' => 'Encuesta post-visita', 'desc' => 'Calificación 1-5 estrellas y comentario. Detecta clientes molestos antes de que se vayan a la competencia.'],
                    ['icon' => '💎', 'title' => 'Suscripción VIP mensual', 'desc' => 'Cobro mensual fijo, lavados ilimitados o paquete grande. Ingreso recurrente garantizado.'],
                    ['icon' => '📦', 'title' => 'Paquetes prepago', 'desc' => '5, 10, 20 lavados a precio especial. Cobras hoy, el cliente regresa.'],
                    ['icon' => '📊', 'title' => 'Reportes y predicción', 'desc' => 'Daily stats, ingresos por servicio, predicción de ganancias del mes. Datos accionables.'],
                ];
                @endphp
                @foreach($features as $i => $feature)
                <div class="group bg-white rounded-2xl p-6 border border-gray-100 hover:border-sky-200 hover:shadow-xl hover:shadow-sky-100/50 transition-all duration-300 hover:-translate-y-2 animate-fade-up" style="animation-delay:{{ $i * 0.05 }}s">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">{{ $feature['icon'] }}</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-animate>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Invierte menos de lo que cuesta un lavado</h2>
                <p class="mt-4 text-lg text-gray-600">El plan que se paga solo con un cliente extra al mes</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto" data-animate>
                @php
                $plans = [
                    ['name' => 'Básico', 'price' => '299', 'features' => ['Hasta 200 clientes', 'Tarjeta de sellos', 'Ruleta de premios', 'WhatsApp manual', 'Reportes básicos'], 'cta' => 'Empezar prueba', 'popular' => false],
                    ['name' => 'Pro', 'price' => '499', 'features' => ['Clientes ilimitados', 'TODO de Básico', 'Niveles VIP', 'Rifas y retos', 'Referidos', 'Cumpleaños automático', 'Encuestas'], 'cta' => 'Empezar prueba', 'popular' => true],
                    ['name' => 'Elite', 'price' => '799', 'features' => ['TODO de Pro', 'Suscripción VIP cliente', 'Paquetes prepago', 'Multi-sucursal', 'Predicción de ingresos', 'Soporte prioritario'], 'cta' => 'Contactar ventas', 'popular' => false],
                ];
                @endphp
                @foreach($plans as $i => $plan)
                <div class="relative bg-white rounded-2xl p-7 transition-all duration-300 hover:-translate-y-2 animate-fade-up {{ $plan['popular'] ? 'border-2 border-sky-500 shadow-xl shadow-sky-100/50 md:scale-105' : 'border border-gray-200 hover:shadow-lg' }}" style="animation-delay:{{ $i * 0.1 }}s">
                    @if($plan['popular'])<div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-gradient-to-r from-sky-600 to-cyan-600 text-white text-xs font-bold rounded-full shadow-lg">Más popular</div>@endif
                    <h3 class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</h3>
                    <div class="mt-4 mb-1"><span class="text-4xl font-extrabold text-gray-900">${{ $plan['price'] }}</span><span class="text-sm text-gray-500"> MXN</span></div>
                    <div class="text-sm text-gray-500 mb-6">por mes</div>
                    <ul class="space-y-3 mb-8">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="#contacto" class="block w-full text-center px-4 py-3 rounded-xl font-semibold transition-all {{ $plan['popular'] ? 'bg-gradient-to-r from-sky-600 to-cyan-600 text-white hover:shadow-lg hover:shadow-sky-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $plan['cta'] }}
                    </a>
                </div>
                @endforeach
            </div>
            <p class="text-center mt-8 text-sm text-gray-500">Todos los planes incluyen 30 días de prueba gratis. Sin tarjeta de crédito.</p>
        </div>
    </section>

    {{-- Contact --}}
    <section id="contacto" class="py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-start">
                <div data-animate>
                    <span class="inline-flex items-center px-3 py-1 bg-sky-50 text-sky-700 text-xs font-semibold rounded-full mb-4 border border-sky-100">CONTACTO</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">¿Listo para fidelizar tus clientes?<br><span class="text-sky-600">Hablemos.</span></h2>
                    <p class="mt-4 text-gray-600 leading-relaxed">Déjanos tus datos y te contactamos para mostrarte cómo LavadoFácil puede transformar tu car wash. Sin compromiso.</p>

                    <div class="mt-10 space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">WhatsApp / Teléfono</div>
                                <a href="https://wa.me/526682493398" target="_blank" class="text-sky-600 hover:text-sky-700 transition font-medium">668 249 3398</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div><div class="font-bold text-gray-900">Email</div><span class="text-gray-600">leooma24@gmail.com</span></div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div><div class="font-bold text-gray-900">Horario</div><span class="text-gray-600">Lunes a Sábado, 9:00 - 19:00</span></div>
                        </div>
                    </div>
                </div>

                <div data-animate>
                    @if(session('contact_success'))
                    <div class="bg-sky-50 border border-sky-200 rounded-2xl p-8 text-center">
                        <div class="text-5xl mb-4">✓</div>
                        <h3 class="text-xl font-bold text-sky-800 mb-2">¡Mensaje enviado!</h3>
                        <p class="text-sky-700">Gracias por tu interés. Te contactamos pronto.</p>
                    </div>
                    @else
                    <form action="{{ route('contact.store') }}" method="POST" class="bg-white rounded-2xl p-8 shadow-xl border border-gray-100 space-y-5">
                        @csrf
                        <div style="position:absolute;left:-9999px" aria-hidden="true">
                            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre *</label>
                                <input type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-3" placeholder="Juan Pérez">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-3" placeholder="tu@email.com">
                                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">WhatsApp</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-3" placeholder="668 123 4567">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ciudad</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-3" placeholder="Culiacán">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre del car wash</label>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-3" placeholder="Auto Spa Premium">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mensaje (opcional)</label>
                            <textarea name="message" rows="3" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-sky-500 focus:ring-sky-500 text-sm py-3" placeholder="Cuéntanos qué necesitas...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-sky-600 to-cyan-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-sky-200 transition-all hover:-translate-y-0.5 text-base">
                            Solicitar demo gratis
                        </button>
                        <p class="text-xs text-gray-400 text-center">Te contactamos en menos de 24 horas. Sin spam.</p>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Final --}}
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-sky-600 via-cyan-600 to-sky-700 animate-gradient"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10" data-animate>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-white leading-tight">Tu competencia ya fideliza.<br><span class="text-sky-200">¿Tú cuándo?</span></h2>
            <p class="mt-6 text-lg text-sky-100 max-w-2xl mx-auto">Cada cliente que viene una vez y nunca regresa es dinero perdido. Empieza hoy y ve la diferencia esta misma semana.</p>
            <a href="#contacto" class="mt-10 inline-flex items-center px-10 py-4 bg-white text-sky-700 font-bold rounded-xl hover:bg-sky-50 transition-all shadow-2xl hover:-translate-y-1 text-lg animate-pulse-glow">
                Solicitar demo gratis &rarr;
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-16 bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <img src="{{ asset('images/lavadofacil_logo.png') }}" alt="LavadoFácil" class="h-10 mb-4 brightness-200">
                    <p class="text-sm text-gray-500">Software de fidelización para car washes mexicanos. Hecho en Sinaloa.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-300 mb-3">Producto</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#features" class="hover:text-sky-400 transition">Funciones</a></li>
                        <li><a href="#pricing" class="hover:text-sky-400 transition">Precios</a></li>
                        <li><a href="{{ url('/lavadodemo/admin/login') }}" class="hover:text-sky-400 transition">Demo en vivo</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-300 mb-3">Empresa</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#contacto" class="hover:text-sky-400 transition">Contacto</a></li>
                        <li><a href="https://wa.me/526682493398" target="_blank" class="hover:text-sky-400 transition">WhatsApp</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-300 mb-3">Acceso</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="{{ url('/lavadodemo/admin/login') }}" class="hover:text-sky-400 transition">Panel del dueño</a></li>
                        <li><a href="{{ url('/lavadodemo') }}" class="hover:text-sky-400 transition">App del cliente</a></li>
                        <li><a href="{{ url('/central') }}" class="hover:text-sky-400 transition">Administración</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-sm text-gray-600">&copy; {{ date('Y') }} LavadoFácil. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

<script>
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            entry.target.querySelectorAll('.animate-fade-up, .animate-slide-in').forEach(el => { el.style.animationPlayState = 'running'; });
        }
    });
}, { threshold: 0.1 });
document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));

window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 100) { navbar.classList.add('shadow-lg'); navbar.classList.remove('border-b'); }
    else { navbar.classList.remove('shadow-lt'); navbar.classList.add('border-b'); }
});
</script>
</body>
</html>
