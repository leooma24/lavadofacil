<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LavadoFácil — El sistema de fidelización #1 para car washes</title>
    <meta name="description" content="Tarjeta de sellos digital, ruleta de premios, niveles VIP, rifas y WhatsApp automático. El SaaS premium para car washes mexicanos.">
    <meta name="theme-color" content="#0c0a09">
    <link rel="icon" type="image/png" href="{{ asset('images/lavadofacil_icon.png') }}">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:title" content="LavadoFácil — El sistema de fidelización #1 para car washes">
    <meta property="og:description" content="Convierte clientes ocasionales en clientes de por vida.">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&family=plus-jakarta-sans:700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { background: #0a0a0a; }
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2, h3, .display { font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.02em; }
        @keyframes gradient { 0%,100%{background-position:0% 50%} 50%{background-position:100% 50%} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-15px)} }
        @keyframes pulse-ring { 0%{box-shadow:0 0 0 0 rgba(14,165,233,0.7)} 70%{box-shadow:0 0 0 20px rgba(14,165,233,0)} 100%{box-shadow:0 0 0 0 rgba(14,165,233,0)} }
        @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
        .animate-gradient { background-size:200% 200%; animation:gradient 6s ease infinite; }
        .animate-fade-up { animation:fadeUp 0.8s ease forwards; }
        .animate-float { animation:float 6s ease-in-out infinite; }
        .animate-pulse-ring { animation:pulse-ring 2s infinite; }
        .delay-100{animation-delay:.1s}.delay-200{animation-delay:.2s}.delay-300{animation-delay:.3s}.delay-400{animation-delay:.4s}.delay-500{animation-delay:.5s}
        [data-animate] { opacity:0; }
        [data-animate].visible { opacity:1; animation:fadeUp 0.8s ease forwards; }
        .gradient-text { background: linear-gradient(135deg, #38bdf8 0%, #06b6d4 50%, #0ea5e9 100%); -webkit-background-clip:text; background-clip:text; color:transparent; }
        .glow-cyan { box-shadow: 0 0 60px rgba(14,165,233,0.4), 0 0 120px rgba(6,182,212,0.2); }
        .grid-bg { background-image: linear-gradient(rgba(14,165,233,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(14,165,233,0.08) 1px, transparent 1px); background-size: 50px 50px; }
        .mesh-bg { background: radial-gradient(circle at 20% 0%, rgba(14,165,233,0.15), transparent 50%), radial-gradient(circle at 80% 30%, rgba(6,182,212,0.15), transparent 50%), radial-gradient(circle at 40% 80%, rgba(59,130,246,0.1), transparent 50%); }
        .shine { background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent); background-size: 200% 100%; animation: shimmer 3s infinite; }
    </style>
</head>
<body class="bg-neutral-950 text-white antialiased overflow-x-hidden">

    {{-- NAVBAR --}}
    <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('images/lavadofacil_logo.png') }}" alt="LavadoFácil" class="h-11">
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm text-neutral-300 hover:text-white transition font-semibold">Funciones</a>
                <a href="#demo" class="text-sm text-neutral-300 hover:text-white transition font-semibold">Demo en vivo</a>
                <a href="#pricing" class="text-sm text-neutral-300 hover:text-white transition font-semibold">Precios</a>
                <a href="#contacto" class="text-sm text-neutral-300 hover:text-white transition font-semibold">Contacto</a>
                <a href="https://wa.me/526682493398" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-cyan-500 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-sky-500/50 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    WhatsApp
                </a>
            </div>
            <button onclick="document.getElementById('m-menu').classList.toggle('hidden')" class="md:hidden p-2 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="m-menu" class="hidden md:hidden bg-neutral-950 border-t border-white/5 px-4 pb-4 space-y-2 pt-2">
            <a href="#features" class="block py-2 text-neutral-300">Funciones</a>
            <a href="#demo" class="block py-2 text-neutral-300">Demo en vivo</a>
            <a href="#pricing" class="block py-2 text-neutral-300">Precios</a>
            <a href="#contacto" class="block py-2 text-neutral-300">Contacto</a>
            <a href="https://wa.me/526682493398" target="_blank" class="block py-3 px-4 bg-sky-500 text-white text-center rounded-lg font-bold">WhatsApp 668 249 3398</a>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="relative pt-40 pb-32 px-4 overflow-hidden mesh-bg">
        <div class="absolute inset-0 grid-bg opacity-40"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-sky-500/10 rounded-full blur-3xl"></div>

        <div class="max-w-5xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 backdrop-blur border border-sky-500/30 text-sky-300 text-xs font-bold uppercase tracking-wider rounded-full mb-8 animate-fade-up">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                </span>
                Diseñado para car washes mexicanos
            </div>

            <h1 class="display text-5xl sm:text-6xl lg:text-8xl font-black tracking-tight leading-[0.95] animate-fade-up delay-100 text-white">
                Convierte clientes<br>
                ocasionales<br>
                <span class="gradient-text animate-gradient">en leales.</span>
            </h1>

            <p class="mt-10 text-lg sm:text-xl text-neutral-400 max-w-2xl mx-auto leading-relaxed animate-fade-up delay-200">
                Mientras tu competencia regala cupones que nadie usa, tú puedes tener un programa de fidelización completo con
                <strong class="text-white">tarjeta digital, ruleta de premios, niveles VIP y WhatsApp automático</strong>.
            </p>

            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-up delay-300">
                <a href="#demo" class="group relative w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sky-500 to-cyan-500 text-white font-bold rounded-xl glow-cyan hover:shadow-2xl hover:shadow-sky-500/50 transition-all hover:-translate-y-1 text-lg overflow-hidden">
                    <span class="absolute inset-0 shine"></span>
                    <span class="relative flex items-center justify-center gap-2">
                        Entra al demo en vivo
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </a>
                <a href="https://wa.me/526682493398?text=Hola%20quiero%20m%C3%A1s%20info%20de%20LavadoF%C3%A1cil" target="_blank" class="w-full sm:w-auto px-8 py-4 bg-white/5 backdrop-blur border border-white/10 text-white font-semibold rounded-xl hover:bg-white/10 transition-all hover:-translate-y-1 flex items-center justify-center gap-2 text-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Hablemos por WhatsApp
                </a>
            </div>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm text-neutral-500 animate-fade-up delay-400">
                <span class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Sin tarjeta de crédito</span>
                <span class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>30 días de prueba</span>
                <span class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Setup en 5 minutos</span>
            </div>
        </div>
    </section>

    {{-- DEMO SECTION (el corazón de la página) --}}
    <section id="demo" class="py-24 relative overflow-hidden border-y border-white/5">
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-950 via-sky-950/20 to-neutral-950"></div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-14" data-animate>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500/10 border border-sky-500/30 text-sky-300 text-xs font-bold uppercase tracking-wider rounded-full mb-4">
                    <span class="w-2 h-2 bg-sky-400 rounded-full animate-pulse"></span>
                    Prueba sin registrarte
                </div>
                <h2 class="display text-4xl sm:text-5xl lg:text-6xl font-black text-white">
                    Entra al demo <span class="gradient-text animate-gradient">ahora mismo</span>
                </h2>
                <p class="mt-5 text-lg text-neutral-400 max-w-2xl mx-auto">Explora los 3 paneles con datos reales. Nada de lo que hagas se guarda.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6" data-animate>
                {{-- PWA Cliente --}}
                <div class="group relative bg-gradient-to-br from-sky-500/10 to-cyan-500/5 rounded-3xl p-8 border border-sky-500/20 hover:border-sky-400/50 transition-all hover:-translate-y-2 hover:shadow-2xl hover:shadow-sky-500/20">
                    <div class="absolute top-4 right-4 px-3 py-1 bg-sky-500 text-white text-[10px] font-black uppercase tracking-wider rounded-full">★ Recomendado</div>
                    <div class="w-14 h-14 bg-gradient-to-br from-sky-400 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-sky-500/50">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">App del cliente</h3>
                    <p class="text-sm text-neutral-400 mb-6 leading-relaxed">Mira lo que ven tus clientes: tarjeta de sellos, ruleta, premios, ranking, niveles VIP.</p>
                    <ul class="space-y-2 mb-6 text-sm text-neutral-300">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Nivel Oro activo</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>VIP mensual ilimitado</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Racha de 6 semanas</li>
                    </ul>
                    <a href="{{ url('/lavadodemo/demo-entrar') }}" class="block w-full text-center px-4 py-3 bg-gradient-to-r from-sky-500 to-cyan-500 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-sky-500/50 transition-all">
                        Entrar directo →
                    </a>
                    <p class="text-center text-xs text-neutral-500 mt-3">Sin credenciales. Un solo clic.</p>
                </div>

                {{-- Panel del dueño --}}
                <div class="group relative bg-white/5 rounded-3xl p-8 border border-white/10 hover:border-white/20 transition-all hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H3m2 4V9a2 2 0 012-2h10a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2z M13 7h8m0 0l-3-3m3 3l-3 3"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Panel del dueño</h3>
                    <p class="text-sm text-neutral-400 mb-6 leading-relaxed">El admin donde el dueño del car wash gestiona clientes, visitas, ranking y WhatsApp.</p>
                    <div class="bg-neutral-900 rounded-xl p-4 mb-6 border border-white/5 font-mono text-xs">
                        <div class="flex justify-between text-neutral-500 mb-1"><span>Email</span></div>
                        <div class="text-sky-300 font-semibold">leooma24@gmail.com</div>
                        <div class="flex justify-between text-neutral-500 mt-3 mb-1"><span>Password</span></div>
                        <div class="text-sky-300 font-semibold">password</div>
                    </div>
                    <a href="{{ url('/lavadodemo/admin/login') }}" target="_blank" class="block w-full text-center px-4 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/10">
                        Abrir panel →
                    </a>
                </div>

                {{-- Super Admin --}}
                <div class="group relative bg-white/5 rounded-3xl p-8 border border-white/10 hover:border-white/20 transition-all hover:-translate-y-2">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-amber-500/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Super admin</h3>
                    <p class="text-sm text-neutral-400 mb-6 leading-relaxed">El panel SaaS donde gestionamos tenants, planes, facturación. Para administradores.</p>
                    <div class="bg-neutral-900 rounded-xl p-4 mb-6 border border-white/5 font-mono text-xs">
                        <div class="flex justify-between text-neutral-500 mb-1"><span>Email</span></div>
                        <div class="text-amber-300 font-semibold">admin@lavadofacil.com</div>
                        <div class="flex justify-between text-neutral-500 mt-3 mb-1"><span>Password</span></div>
                        <div class="text-amber-300 font-semibold">password</div>
                    </div>
                    <a href="{{ url('/central/login') }}" target="_blank" class="block w-full text-center px-4 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all border border-white/10">
                        Abrir panel →
                    </a>
                </div>
            </div>

            <div class="mt-10 text-center" data-animate>
                <div class="inline-flex items-center gap-3 px-6 py-3 bg-amber-500/10 border border-amber-500/30 rounded-full text-amber-300 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><strong>Modo demo:</strong> puedes navegar libremente — ningún cambio se guarda.</span>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <section class="py-16 border-b border-white/5">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div data-animate>
                <div class="display text-4xl sm:text-5xl font-black gradient-text">+45%</div>
                <div class="text-sm text-neutral-500 mt-2 font-medium">Más visitas recurrentes</div>
            </div>
            <div data-animate class="delay-100">
                <div class="display text-4xl sm:text-5xl font-black gradient-text">3x</div>
                <div class="text-sm text-neutral-500 mt-2 font-medium">Más referidos por cliente</div>
            </div>
            <div data-animate class="delay-200">
                <div class="display text-4xl sm:text-5xl font-black gradient-text">−60%</div>
                <div class="text-sm text-neutral-500 mt-2 font-medium">Clientes dormidos</div>
            </div>
            <div data-animate class="delay-300">
                <div class="display text-4xl sm:text-5xl font-black gradient-text">5 min</div>
                <div class="text-sm text-neutral-500 mt-2 font-medium">Para empezar</div>
            </div>
        </div>
    </section>

    {{-- PAIN vs SOLUTION --}}
    <section id="problema" class="py-28">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-animate>
                <h2 class="display text-4xl sm:text-5xl font-black text-white">¿Te suena familiar?</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-8 items-start">
                <div class="space-y-4" data-animate>
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 text-red-300 rounded-full text-xs font-bold uppercase tracking-wider border border-red-500/30">Sin LavadoFácil</span>
                    </div>
                    @php
                    $pains = [
                        ['Tarjetas de cartón perdidas', 'El cliente la deja en el carro, se moja, se pierde. Nunca completa los sellos.'],
                        ['No sabes quién es tu mejor cliente', 'Tratas igual al que viene 3 veces al año que al que viene cada semana.'],
                        ['Promos por WhatsApp manuales', 'Mandas mensajes uno por uno, te falta tiempo, dejas a medias.'],
                        ['Decides a ciegas', 'No sabes cuántos clientes dejaron de venir ni qué servicios dejan más utilidad.'],
                    ];
                    @endphp
                    @foreach($pains as $pain)
                    <div class="flex gap-4 p-5 bg-red-500/5 rounded-2xl border border-red-500/10">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white">{{ $pain[0] }}</h3>
                            <p class="text-sm text-neutral-400 mt-1">{{ $pain[1] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="space-y-4" data-animate>
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500/10 text-sky-300 rounded-full text-xs font-bold uppercase tracking-wider border border-sky-500/30">Con LavadoFácil</span>
                    </div>
                    @php
                    $solutions = [
                        ['Tarjeta de sellos digital con ruleta', 'Cada visita suma un sello. Al completarla, giran la ruleta y ganan un premio. Adicción garantizada.'],
                        ['Niveles Bronce / Plata / Oro / Platino', 'Tus mejores clientes suben automático. Saben que valoras su lealtad.'],
                        ['WhatsApp con plantillas listas', 'Cumpleaños, recuperación de dormidos, recordatorios — un clic y listo.'],
                        ['Reportes en tiempo real', 'Ranking del mes, ingresos por servicio, predicción de utilidades.'],
                    ];
                    @endphp
                    @foreach($solutions as $sol)
                    <div class="flex gap-4 p-5 bg-sky-500/5 rounded-2xl border border-sky-500/20">
                        <div class="flex-shrink-0 w-10 h-10 bg-sky-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white">{{ $sol[0] }}</h3>
                            <p class="text-sm text-neutral-400 mt-1">{{ $sol[1] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section id="features" class="py-28 relative overflow-hidden">
        <div class="absolute inset-0 mesh-bg opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-animate>
                <span class="inline-flex items-center px-4 py-2 bg-sky-500/10 border border-sky-500/30 text-sky-300 text-xs font-bold uppercase tracking-wider rounded-full mb-4">14 módulos</span>
                <h2 class="display text-4xl sm:text-5xl lg:text-6xl font-black text-white">Todo lo que tu car wash<br>necesita</h2>
                <p class="mt-5 text-lg text-neutral-400 max-w-2xl mx-auto">Cada función fue diseñada con dueños de car wash mexicanos en mente</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
                @php
                $features = [
                    ['title' => 'Tarjeta de 8 sellos', 'desc' => 'Cada visita suma. Al completar, gira la ruleta y gana un premio aleatorio.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
                    ['title' => 'Ruleta de premios', 'desc' => 'Configura premios con probabilidad personalizada. Lavado gratis, descuentos y más.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 10h14a1 1 0 011 1v9a2 2 0 01-2 2H6a2 2 0 01-2-2v-9a1 1 0 011-1z"/>'],
                    ['title' => 'Rifa mensual', 'desc' => 'Cada visita = ticket. Un sorteo grande al mes que mantiene a los clientes regresando.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>'],
                    ['title' => 'Niveles VIP', 'desc' => 'Bronce, Plata, Oro, Platino. Suben automático según gasto. Beneficios por nivel.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
                    ['title' => 'Programa de referidos', 'desc' => 'Código único por cliente. Referidor gana premio, referido gana descuento.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                    ['title' => 'Cumpleaños automático', 'desc' => 'WhatsApp + descuento el día del cumple del cliente. Sin que lo recuerdes.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>'],
                    ['title' => 'Racha de visitas', 'desc' => 'Cuenta semanas consecutivas. Premios por mantener la racha. Adicción dulce.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
                    ['title' => 'WhatsApp manual', 'desc' => '20 plantillas listas. Un clic abre wa.me con mensaje pre-llenado. Sin pagar API.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'],
                    ['title' => 'Reactivación de dormidos', 'desc' => 'Lista de clientes que no han venido en 30+ días. Botón directo WhatsApp.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>'],
                    ['title' => 'Ranking del mes', 'desc' => 'Top clientes por visitas y por gasto. Premio al #1 de cada mes.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
                    ['title' => 'Encuestas post-visita', 'desc' => 'Calificación 1-5 y comentario. Detecta molestos antes de que se vayan.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>'],
                    ['title' => 'Suscripción VIP', 'desc' => 'Cobro mensual fijo con lavados ilimitados. Ingreso recurrente garantizado.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    ['title' => 'Paquetes prepago', 'desc' => '5, 10, 20 lavados a precio especial. Cobras hoy, el cliente regresa.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
                    ['title' => 'Reportes y predicción', 'desc' => 'Daily stats, ingresos por servicio, predicción de ganancias del mes.', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>'],
                ];
                @endphp
                @foreach($features as $i => $f)
                <div class="group bg-white/5 backdrop-blur rounded-2xl p-6 border border-white/10 hover:border-sky-400/50 hover:bg-sky-500/5 transition-all duration-300 hover:-translate-y-1" data-animate style="animation-delay:{{ $i * 0.04 }}s">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-sky-500/30 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $f['svg'] !!}</svg>
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">{{ $f['title'] }}</h3>
                    <p class="text-xs text-neutral-400 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PRICING --}}
    <section id="pricing" class="py-28 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-animate>
                <h2 class="display text-4xl sm:text-5xl lg:text-6xl font-black text-white">Invierte menos<br>de lo que cuesta un lavado</h2>
                <p class="mt-5 text-lg text-neutral-400">El plan que se paga solo con un cliente extra al mes</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                @php
                $plans = [
                    ['name' => 'Básico', 'price' => '299', 'features' => ['Hasta 200 clientes', 'Tarjeta de sellos', 'Ruleta de premios', 'WhatsApp manual', 'Reportes básicos'], 'popular' => false],
                    ['name' => 'Pro', 'price' => '499', 'features' => ['Clientes ilimitados', 'TODO de Básico', 'Niveles VIP', 'Rifas y retos', 'Referidos', 'Cumpleaños automático', 'Encuestas'], 'popular' => true],
                    ['name' => 'Elite', 'price' => '799', 'features' => ['TODO de Pro', 'Suscripción VIP cliente', 'Paquetes prepago', 'Multi-sucursal', 'Predicción de ingresos', 'Soporte prioritario'], 'popular' => false],
                ];
                @endphp
                @foreach($plans as $i => $plan)
                <div data-animate class="relative rounded-3xl p-8 transition-all duration-300 hover:-translate-y-2 {{ $plan['popular'] ? 'bg-gradient-to-br from-sky-500/20 via-cyan-500/10 to-transparent border-2 border-sky-400 md:scale-105 shadow-2xl shadow-sky-500/20' : 'bg-white/5 border border-white/10 hover:border-white/20' }}" style="animation-delay:{{ $i * 0.1 }}s">
                    @if($plan['popular'])
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-5 py-1.5 bg-gradient-to-r from-sky-400 to-cyan-400 text-neutral-950 text-xs font-black uppercase tracking-wider rounded-full shadow-lg">★ Más popular</div>
                    @endif
                    <h3 class="text-xl font-bold text-white">{{ $plan['name'] }}</h3>
                    <div class="mt-5 mb-1 flex items-baseline gap-1">
                        <span class="display text-5xl font-black text-white">${{ $plan['price'] }}</span>
                        <span class="text-sm text-neutral-500 font-medium">MXN/mes</span>
                    </div>
                    <div class="text-sm text-neutral-500 mb-8">IVA incluido</div>
                    <ul class="space-y-3 mb-8">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-start gap-2 text-sm text-neutral-300">
                            <svg class="w-4 h-4 text-sky-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="https://wa.me/526682493398?text=Hola%20quiero%20el%20plan%20{{ urlencode($plan['name']) }}" target="_blank" class="block w-full text-center px-4 py-3.5 rounded-xl font-bold transition-all {{ $plan['popular'] ? 'bg-gradient-to-r from-sky-400 to-cyan-400 text-neutral-950 hover:shadow-lg hover:shadow-sky-500/50' : 'bg-white/10 text-white border border-white/10 hover:bg-white/15' }}">
                        Empezar con {{ $plan['name'] }}
                    </a>
                </div>
                @endforeach
            </div>
            <p class="text-center mt-10 text-sm text-neutral-500">30 días de prueba gratis en todos los planes · Sin tarjeta de crédito</p>
        </div>
    </section>

    {{-- CONTACT --}}
    <section id="contacto" class="py-28 border-t border-white/5">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-start">
                <div data-animate>
                    <span class="inline-flex items-center px-4 py-2 bg-sky-500/10 border border-sky-500/30 text-sky-300 text-xs font-bold uppercase tracking-wider rounded-full mb-5">Contacto</span>
                    <h2 class="display text-4xl sm:text-5xl font-black text-white leading-tight">Hablemos por<br><span class="gradient-text animate-gradient">WhatsApp</span></h2>
                    <p class="mt-5 text-lg text-neutral-400">La forma más rápida. Te muestro el sistema, contesto preguntas y te ayudo a empezar.</p>

                    <a href="https://wa.me/526682493398?text=Hola%20quiero%20m%C3%A1s%20info%20de%20LavadoF%C3%A1cil" target="_blank" class="mt-8 inline-flex items-center gap-3 px-6 py-5 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold rounded-2xl hover:shadow-2xl hover:shadow-green-500/50 transition-all hover:-translate-y-1 text-lg">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        <span>
                            <div class="text-xs font-normal text-green-100">Omar Lerma — Fundador</div>
                            <div class="text-2xl font-black">668 249 3398</div>
                        </span>
                    </a>

                    <div class="mt-10 space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-11 h-11 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs text-neutral-500">Email</div>
                                <a href="mailto:leooma24@gmail.com" class="text-white font-semibold hover:text-sky-300 transition">leooma24@gmail.com</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-11 h-11 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs text-neutral-500">Horario</div>
                                <div class="text-white font-semibold">Lunes a Sábado, 9:00 - 19:00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-animate class="delay-200">
                    @if(session('contact_success'))
                    <div class="bg-gradient-to-br from-sky-500/20 to-cyan-500/10 border border-sky-500/30 rounded-3xl p-10 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-sky-400 to-cyan-400 rounded-full flex items-center justify-center mx-auto mb-5">
                            <svg class="w-8 h-8 text-neutral-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="display text-2xl font-black text-white mb-2">¡Mensaje enviado!</h3>
                        <p class="text-neutral-400">Gracias por tu interés. Te contacto pronto por WhatsApp.</p>
                    </div>
                    @else
                    <form action="{{ route('contact.store') }}" method="POST" class="bg-white/5 backdrop-blur rounded-3xl p-8 border border-white/10 space-y-5">
                        @csrf
                        <div style="position:absolute;left:-9999px" aria-hidden="true"><input type="text" name="website_url" tabindex="-1" autocomplete="off"></div>
                        <h3 class="text-xl font-bold text-white mb-2">O déjame tus datos</h3>
                        <p class="text-sm text-neutral-400 -mt-3">Te contacto en menos de 24 horas.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-neutral-400 uppercase tracking-wider mb-2">Nombre *</label>
                                <input type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-xl bg-neutral-900 border border-white/10 text-white placeholder-neutral-600 focus:border-sky-500 focus:ring-sky-500 text-sm py-3 px-4" placeholder="Tu nombre">
                                @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-neutral-400 uppercase tracking-wider mb-2">Email *</label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="w-full rounded-xl bg-neutral-900 border border-white/10 text-white placeholder-neutral-600 focus:border-sky-500 focus:ring-sky-500 text-sm py-3 px-4" placeholder="tu@email.com">
                                @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-neutral-400 uppercase tracking-wider mb-2">WhatsApp</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl bg-neutral-900 border border-white/10 text-white placeholder-neutral-600 focus:border-sky-500 focus:ring-sky-500 text-sm py-3 px-4" placeholder="668 123 4567">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-neutral-400 uppercase tracking-wider mb-2">Ciudad</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded-xl bg-neutral-900 border border-white/10 text-white placeholder-neutral-600 focus:border-sky-500 focus:ring-sky-500 text-sm py-3 px-4" placeholder="Culiacán">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-neutral-400 uppercase tracking-wider mb-2">Nombre del car wash</label>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" class="w-full rounded-xl bg-neutral-900 border border-white/10 text-white placeholder-neutral-600 focus:border-sky-500 focus:ring-sky-500 text-sm py-3 px-4" placeholder="Auto Spa Premium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-neutral-400 uppercase tracking-wider mb-2">Mensaje</label>
                            <textarea name="message" rows="3" class="w-full rounded-xl bg-neutral-900 border border-white/10 text-white placeholder-neutral-600 focus:border-sky-500 focus:ring-sky-500 text-sm py-3 px-4" placeholder="¿En qué te puedo ayudar?">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-sky-500 to-cyan-500 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-sky-500/50 transition-all hover:-translate-y-0.5 text-base">
                            Enviar mensaje
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section class="py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-600 via-cyan-600 to-blue-700 animate-gradient"></div>
        <div class="absolute inset-0 grid-bg opacity-20"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10" data-animate>
            <h2 class="display text-4xl sm:text-6xl font-black text-white leading-tight">
                Tu competencia ya fideliza.<br>
                <span class="text-sky-200">¿Tú cuándo?</span>
            </h2>
            <p class="mt-8 text-xl text-sky-100 max-w-2xl mx-auto">Cada cliente que viene una vez y nunca regresa es dinero perdido. Empieza hoy.</p>
            <a href="https://wa.me/526682493398?text=Hola%20quiero%20empezar%20con%20LavadoF%C3%A1cil" target="_blank" class="mt-12 inline-flex items-center gap-3 px-10 py-5 bg-white text-sky-700 font-black rounded-2xl hover:bg-sky-50 transition-all shadow-2xl hover:-translate-y-1 text-xl animate-pulse-ring">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Contactar por WhatsApp
            </a>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="py-16 bg-black border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-2">
                    <img src="{{ asset('images/lavadofacil_logo.png') }}" alt="LavadoFácil" class="h-12 mb-5">
                    <p class="text-sm text-neutral-500 max-w-sm leading-relaxed">Software de fidelización premium para car washes mexicanos. Hecho en Sinaloa con 🫶.</p>
                    <a href="https://wa.me/526682493398" target="_blank" class="inline-flex items-center gap-2 mt-5 text-sm text-neutral-400 hover:text-sky-300 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        668 249 3398
                    </a>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider">Producto</h4>
                    <ul class="space-y-3 text-sm text-neutral-500">
                        <li><a href="#features" class="hover:text-sky-300 transition">Funciones</a></li>
                        <li><a href="#pricing" class="hover:text-sky-300 transition">Precios</a></li>
                        <li><a href="#demo" class="hover:text-sky-300 transition">Demo en vivo</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4 text-sm uppercase tracking-wider">Acceso</h4>
                    <ul class="space-y-3 text-sm text-neutral-500">
                        <li><a href="{{ url('/lavadodemo/demo-entrar') }}" class="hover:text-sky-300 transition">App del cliente</a></li>
                        <li><a href="{{ url('/lavadodemo/admin/login') }}" class="hover:text-sky-300 transition">Panel del dueño</a></li>
                        <li><a href="{{ url('/central/login') }}" class="hover:text-sky-300 transition">Super admin</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/5 pt-8 text-center">
                <p class="text-sm text-neutral-600">&copy; {{ date('Y') }} LavadoFácil. Hecho por <a href="https://wa.me/526682493398" class="text-neutral-500 hover:text-sky-300 transition">Omar Lerma</a>.</p>
            </div>
        </div>
    </footer>

<script>
const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('[data-animate]').forEach(el => obs.observe(el));

const nav = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        nav.classList.add('bg-neutral-950/80', 'backdrop-blur-xl');
    } else {
        nav.classList.remove('bg-neutral-950/80', 'backdrop-blur-xl');
    }
});
</script>
</body>
</html>
