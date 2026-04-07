{{-- Icono SVG por nivel — uso: @include('customer.partials.level-icon', ['level' => $customer->level, 'class' => 'w-6 h-6']) --}}
@php
    $colors = [
        'bronze'   => ['#cd7f32', '#8b4513'],
        'silver'   => ['#cbd5e1', '#64748b'],
        'gold'     => ['#fde047', '#f59e0b'],
        'platinum' => ['#e0e7ff', '#818cf8'],
    ];
    [$c1, $c2] = $colors[$level] ?? $colors['bronze'];
    $cls = $class ?? 'w-6 h-6';
    $gradId = 'lv-'.$level.'-'.uniqid();
@endphp
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="{{ $cls }}">
    <defs>
        <linearGradient id="{{ $gradId }}" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="{{ $c1 }}"/>
            <stop offset="1" stop-color="{{ $c2 }}"/>
        </linearGradient>
    </defs>
    <path fill="url(#{{ $gradId }})" stroke="rgba(0,0,0,0.3)" stroke-width="0.5"
          d="M12 2L8.5 5.5H4v4.5L1 13l3 3v4.5h4.5L12 22l3.5-1.5H20V16l3-3-3-3.5V5h-4.5L12 2zm0 4.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9z"/>
</svg>
