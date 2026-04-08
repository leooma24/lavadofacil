<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════════════
   LavadoFácil — Custom Filament Theme v2
   ═══════════════════════════════════════════════════════════ */

:root {
    --lf-accent: #06b6d4;
    --lf-accent-dark: #0891b2;
    --lf-accent-2: #6366f1;
    --lf-accent-glow: rgba(6, 182, 212, 0.3);
    --lf-bg-light: #f1f5f9;
    --lf-bg-dark: #0a0e1a;
}

* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

html, body, .fi-body, .fi-layout {
    font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
    letter-spacing: -0.011em;
}

/* ═══════════════════════════════════════════════════════════
   BACKGROUNDS
   ═══════════════════════════════════════════════════════════ */
.fi-body {
    background:
        radial-gradient(circle at 0% 0%, rgba(6, 182, 212, 0.08) 0%, transparent 40%),
        radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.06) 0%, transparent 40%),
        radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.03) 0%, transparent 50%),
        #f8fafc !important;
    background-attachment: fixed;
    min-height: 100vh;
}

.dark .fi-body {
    background:
        radial-gradient(circle at 0% 0%, rgba(6, 182, 212, 0.12) 0%, transparent 40%),
        radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
        #0a0e1a !important;
    background-attachment: fixed;
}

/* Sutile grid pattern overlay */
.fi-body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(148, 163, 184, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(148, 163, 184, 0.04) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
    z-index: 0;
}

.fi-main, .fi-sidebar, .fi-topbar {
    position: relative;
    z-index: 1;
}

/* ═══════════════════════════════════════════════════════════
   SIDEBAR
   ═══════════════════════════════════════════════════════════ */
.fi-sidebar {
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(20px) saturate(180%);
    border-right: 1px solid rgba(15, 23, 42, 0.06) !important;
    box-shadow: 4px 0 30px rgba(15, 23, 42, 0.04);
}

.dark .fi-sidebar {
    background: rgba(15, 23, 42, 0.7) !important;
    backdrop-filter: blur(20px) saturate(180%);
    border-right: 1px solid rgba(148, 163, 184, 0.08) !important;
    box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
}

.fi-sidebar-header {
    padding: 1.5rem 1.25rem !important;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06) !important;
}

.dark .fi-sidebar-header {
    border-bottom-color: rgba(148, 163, 184, 0.08) !important;
}

.fi-sidebar-nav {
    padding: 1rem 0.85rem !important;
}

.fi-sidebar-group-label {
    text-transform: uppercase;
    font-size: 0.65rem !important;
    letter-spacing: 0.12em !important;
    font-weight: 700 !important;
    opacity: 0.45;
    padding: 1.25rem 0.85rem 0.5rem !important;
}

.fi-sidebar-item {
    margin-bottom: 4px;
}

.fi-sidebar-item-button {
    border-radius: 0.85rem !important;
    padding: 0.75rem 0.9rem !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    font-weight: 500 !important;
    font-size: 0.875rem !important;
    color: #475569 !important;
}

.dark .fi-sidebar-item-button {
    color: #cbd5e1 !important;
}

.fi-sidebar-item-button:hover {
    background: rgba(6, 182, 212, 0.08) !important;
    color: var(--lf-accent) !important;
    transform: translateX(3px);
}

.fi-sidebar-item-button .fi-sidebar-item-icon {
    color: inherit !important;
    opacity: 0.7;
}

.fi-sidebar-item-active .fi-sidebar-item-button,
.fi-sidebar-item-button.fi-active {
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(99, 102, 241, 0.1)) !important;
    color: var(--lf-accent-dark) !important;
    font-weight: 700 !important;
    box-shadow:
        0 4px 20px rgba(6, 182, 212, 0.18),
        inset 0 1px 0 rgba(255, 255, 255, 0.4);
    position: relative;
}

.dark .fi-sidebar-item-active .fi-sidebar-item-button,
.dark .fi-sidebar-item-button.fi-active {
    color: #67e8f9 !important;
    background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(99, 102, 241, 0.15)) !important;
}

.fi-sidebar-item-active .fi-sidebar-item-button::before,
.fi-sidebar-item-button.fi-active::before {
    content: '';
    position: absolute;
    left: -0.85rem;
    top: 25%;
    bottom: 25%;
    width: 4px;
    background: linear-gradient(180deg, var(--lf-accent), var(--lf-accent-2));
    border-radius: 0 4px 4px 0;
    box-shadow: 0 0 10px var(--lf-accent-glow);
}

.fi-sidebar-item-active .fi-sidebar-item-icon {
    color: var(--lf-accent) !important;
    opacity: 1;
}

/* ═══════════════════════════════════════════════════════════
   TOPBAR
   ═══════════════════════════════════════════════════════════ */
.fi-topbar > nav {
    background: rgba(255, 255, 255, 0.8) !important;
    backdrop-filter: blur(20px) saturate(180%);
    border-bottom: 1px solid rgba(15, 23, 42, 0.06) !important;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}

.dark .fi-topbar > nav {
    background: rgba(15, 23, 42, 0.8) !important;
    border-bottom-color: rgba(148, 163, 184, 0.08) !important;
}

/* ═══════════════════════════════════════════════════════════
   PAGE HEADER
   ═══════════════════════════════════════════════════════════ */
.fi-header {
    margin-bottom: 2rem !important;
}

.fi-header-heading {
    font-weight: 800 !important;
    font-size: 2rem !important;
    letter-spacing: -0.025em !important;
    background: linear-gradient(135deg, #0f172a 0%, #475569 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.dark .fi-header-heading {
    background: linear-gradient(135deg, #f8fafc 0%, #cbd5e1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.fi-header-subheading {
    opacity: 0.6;
    font-weight: 500;
    margin-top: 0.5rem;
}

/* ═══════════════════════════════════════════════════════════
   SECTIONS / CARDS
   ═══════════════════════════════════════════════════════════ */
.fi-section {
    background: rgba(255, 255, 255, 0.7) !important;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(15, 23, 42, 0.06) !important;
    border-radius: 1.25rem !important;
    box-shadow:
        0 4px 24px rgba(15, 23, 42, 0.06),
        0 1px 0 rgba(255, 255, 255, 0.8) inset !important;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.fi-section:hover {
    border-color: rgba(6, 182, 212, 0.2) !important;
    box-shadow:
        0 8px 32px rgba(15, 23, 42, 0.1),
        0 0 0 1px rgba(6, 182, 212, 0.1),
        0 1px 0 rgba(255, 255, 255, 0.8) inset !important;
}

.dark .fi-section {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.5)) !important;
    border-color: rgba(148, 163, 184, 0.08) !important;
    box-shadow:
        0 10px 40px rgba(0, 0, 0, 0.3),
        0 1px 0 rgba(255, 255, 255, 0.05) inset !important;
}

.dark .fi-section:hover {
    border-color: rgba(6, 182, 212, 0.3) !important;
    box-shadow:
        0 10px 40px rgba(0, 0, 0, 0.4),
        0 0 30px var(--lf-accent-glow) !important;
}

.fi-section-header {
    padding: 1.25rem 1.5rem !important;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06) !important;
}

.dark .fi-section-header {
    border-bottom-color: rgba(148, 163, 184, 0.08) !important;
}

.fi-section-header-heading {
    font-weight: 700 !important;
    font-size: 1rem !important;
    letter-spacing: -0.01em;
}

.fi-section-content {
    padding: 1.5rem !important;
}

/* ═══════════════════════════════════════════════════════════
   STATS WIDGET — la pieza estrella
   ═══════════════════════════════════════════════════════════ */
.fi-wi-stats-overview-stat {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
    border: 1px solid rgba(15, 23, 42, 0.06) !important;
    border-radius: 1.25rem !important;
    padding: 1.5rem 1.75rem !important;
    box-shadow:
        0 4px 20px rgba(15, 23, 42, 0.04),
        0 1px 0 rgba(255, 255, 255, 0.9) inset !important;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.fi-wi-stats-overview-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--lf-accent), var(--lf-accent-2));
    opacity: 0.8;
}

.fi-wi-stats-overview-stat:hover {
    transform: translateY(-3px);
    box-shadow:
        0 12px 32px rgba(15, 23, 42, 0.1),
        0 0 0 1px rgba(6, 182, 212, 0.15),
        0 1px 0 rgba(255, 255, 255, 0.9) inset !important;
}

.dark .fi-wi-stats-overview-stat {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.6) 100%) !important;
    border-color: rgba(148, 163, 184, 0.1) !important;
}

.fi-wi-stats-overview-stat-value {
    font-size: 2.5rem !important;
    font-weight: 800 !important;
    letter-spacing: -0.035em !important;
    line-height: 1.1 !important;
    background: linear-gradient(135deg, var(--lf-accent) 0%, var(--lf-accent-2) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0.25rem 0 !important;
}

.fi-wi-stats-overview-stat-label {
    font-weight: 700 !important;
    text-transform: uppercase;
    font-size: 0.7rem !important;
    letter-spacing: 0.1em;
    color: #64748b !important;
}

.dark .fi-wi-stats-overview-stat-label {
    color: #94a3b8 !important;
}

.fi-wi-stats-overview-stat-description {
    font-size: 0.825rem !important;
    font-weight: 500 !important;
    color: #475569 !important;
    margin-top: 0.5rem;
}

.dark .fi-wi-stats-overview-stat-description {
    color: #cbd5e1 !important;
}

.fi-wi-stats-overview-stat-description-icon {
    color: var(--lf-accent) !important;
}

/* Sparkline chart embedded in stat */
.fi-wi-stats-overview-stat-chart {
    margin-top: 1rem;
    margin-left: -1.75rem;
    margin-right: -1.75rem;
    margin-bottom: -1.5rem;
    height: 60px;
    opacity: 0.85;
}

/* ═══════════════════════════════════════════════════════════
   TABLES
   ═══════════════════════════════════════════════════════════ */
.fi-ta {
    border-radius: 1rem !important;
    overflow: hidden;
}

.fi-ta-header {
    background: rgba(248, 250, 252, 0.8) !important;
}

.dark .fi-ta-header {
    background: rgba(15, 23, 42, 0.5) !important;
}

.fi-ta-header-cell {
    font-weight: 700 !important;
    text-transform: uppercase;
    font-size: 0.7rem !important;
    letter-spacing: 0.06em;
    color: #64748b !important;
    padding: 0.85rem 1rem !important;
}

.dark .fi-ta-header-cell {
    color: #94a3b8 !important;
}

.fi-ta-row {
    transition: background 0.15s ease;
}

.fi-ta-row:hover {
    background: rgba(6, 182, 212, 0.04) !important;
}

.dark .fi-ta-row:hover {
    background: rgba(6, 182, 212, 0.06) !important;
}

.fi-ta-cell {
    padding: 1rem !important;
    font-size: 0.875rem;
}

/* ═══════════════════════════════════════════════════════════
   BUTTONS
   ═══════════════════════════════════════════════════════════ */
.fi-btn {
    font-weight: 600 !important;
    border-radius: 0.75rem !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    letter-spacing: -0.01em;
}

.fi-btn-color-primary,
.fi-btn[class*="primary"] {
    background: linear-gradient(135deg, var(--lf-accent) 0%, var(--lf-accent-dark) 100%) !important;
    box-shadow:
        0 4px 14px rgba(6, 182, 212, 0.35),
        0 1px 0 rgba(255, 255, 255, 0.2) inset !important;
    border: none !important;
    color: white !important;
}

.fi-btn-color-primary:hover {
    transform: translateY(-2px);
    box-shadow:
        0 8px 24px rgba(6, 182, 212, 0.45),
        0 1px 0 rgba(255, 255, 255, 0.2) inset !important;
}

.fi-btn-color-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35) !important;
    color: white !important;
}

.fi-btn-color-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35) !important;
    color: white !important;
}

.fi-btn-color-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35) !important;
    color: white !important;
}

/* ═══════════════════════════════════════════════════════════
   BADGES
   ═══════════════════════════════════════════════════════════ */
.fi-badge {
    font-weight: 700 !important;
    letter-spacing: 0.02em;
    border-radius: 9999px !important;
    padding: 0.3rem 0.8rem !important;
    font-size: 0.7rem !important;
    text-transform: uppercase;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* ═══════════════════════════════════════════════════════════
   INPUTS
   ═══════════════════════════════════════════════════════════ */
.fi-input,
.fi-select-input,
.fi-textarea {
    border-radius: 0.75rem !important;
    transition: all 0.2s ease;
    font-weight: 500;
    background: rgba(248, 250, 252, 0.6) !important;
}

.dark .fi-input,
.dark .fi-select-input,
.dark .fi-textarea {
    background: rgba(15, 23, 42, 0.6) !important;
}

.fi-input:focus,
.fi-select-input:focus,
.fi-textarea:focus {
    border-color: var(--lf-accent) !important;
    box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15) !important;
    background: white !important;
}

/* ═══════════════════════════════════════════════════════════
   MISC
   ═══════════════════════════════════════════════════════════ */
.fi-modal-window {
    border-radius: 1.25rem !important;
    backdrop-filter: blur(20px);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25) !important;
}

.fi-no-notification {
    border-radius: 1rem !important;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(15, 23, 42, 0.06) !important;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15) !important;
}

.fi-dropdown-panel {
    border-radius: 1rem !important;
    border: 1px solid rgba(15, 23, 42, 0.06) !important;
    backdrop-filter: blur(20px);
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15) !important;
}

.fi-tabs {
    border-radius: 0.85rem !important;
    padding: 0.3rem !important;
    background: rgba(148, 163, 184, 0.1) !important;
}

.fi-tabs-item {
    border-radius: 0.6rem !important;
    font-weight: 600 !important;
    transition: all 0.2s ease;
}

.fi-tabs-item-active {
    background: linear-gradient(135deg, var(--lf-accent), var(--lf-accent-dark)) !important;
    color: white !important;
    box-shadow: 0 4px 14px rgba(6, 182, 212, 0.4);
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.25);
    border-radius: 10px;
    border: 2px solid transparent;
    background-clip: padding-box;
}
::-webkit-scrollbar-thumb:hover {
    background: rgba(148, 163, 184, 0.45);
    background-clip: padding-box;
    border: 2px solid transparent;
}

/* Avatar circle in topbar */
.fi-avatar {
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.25);
}

/* ═══════════════════════════════════════════════════════════
   MOBILE — ajustes para que el admin se vea bien en celular
   ═══════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
    /* Menos padding en toda la página para ganar ancho */
    .fi-main, .fi-page-content { padding: 0.75rem !important; }

    /* Heading más pequeño en mobile (2rem es gigante para cel) */
    .fi-header-heading { font-size: 1.35rem !important; }
    .fi-header { margin-bottom: 1rem !important; }
    .fi-header-subheading { font-size: 0.8rem; }

    /* Sections con menos padding y borde menos pesado */
    .fi-section-header { padding: 0.9rem 1rem !important; }
    .fi-section-content { padding: 1rem !important; }
    .fi-section-header-heading { font-size: 0.9rem !important; }

    /* Stats widgets: 1 por fila no pierde info */
    .fi-wi-stats-overview { grid-template-columns: 1fr !important; gap: 0.75rem !important; }
    .fi-wi-stats-overview-stat { padding: 1.1rem 1.25rem !important; }
    .fi-wi-stats-overview-stat-value { font-size: 1.85rem !important; }
    .fi-wi-stats-overview-stat-chart { display: none; } /* sparkline estorba en cel */

    /* Tablas scrolleables horizontalmente en cel */
    .fi-ta-content { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .fi-ta-cell { padding: 0.65rem 0.75rem !important; font-size: 0.8rem; }
    .fi-ta-header-cell { padding: 0.6rem 0.75rem !important; font-size: 0.62rem !important; }

    /* Botones de acción en header se apilan abajo */
    .fi-header-actions { flex-wrap: wrap; gap: 0.5rem; }
    .fi-header-actions .fi-btn { font-size: 0.8rem !important; padding: 0.55rem 0.85rem !important; }

    /* Forms: 1 columna siempre, menos padding */
    .fi-fo-component-ctn { padding: 0 !important; }

    /* Sidebar en mobile: overlay amplio con sombra fuerte */
    .fi-sidebar {
        width: 85vw !important;
        max-width: 320px !important;
        box-shadow: 10px 0 50px rgba(0,0,0,0.5) !important;
    }
    .fi-sidebar .fi-sidebar-nav { padding: 0.75rem 0.6rem !important; }
    .fi-sidebar-item-button { padding: 0.7rem 0.8rem !important; font-size: 0.9rem !important; }

    /* Inputs: ancho completo, texto que no se corte */
    .fi-input, .fi-select-input, .fi-textarea {
        width: 100% !important;
        min-width: 0 !important;
        font-size: 16px !important; /* evita zoom en iOS al hacer focus */
    }
    .fi-fo-field-wrp { min-width: 0 !important; }
    .fi-fo-component-ctn > * { min-width: 0 !important; }

    /* Grid de forms: siempre 1 columna en mobile para que nada se corte */
    .fi-fo-component-ctn .grid { grid-template-columns: 1fr !important; }

    /* Topbar más compacto */
    .fi-topbar > nav { padding: 0.5rem 0.75rem !important; }
    .fi-topbar .fi-logo img { max-height: 1.75rem !important; }

    /* Botones del theme toggle más pequeños */
    .lf-theme-toggle { width: 34px; height: 34px; }
    .lf-theme-toggle svg { width: 17px; height: 17px; }

    /* Modals ocupan casi toda la pantalla */
    .fi-modal-window { border-radius: 1rem !important; margin: 0.5rem !important; max-width: calc(100vw - 1rem) !important; }

    /* Badges un pelín más chicos */
    .fi-badge { padding: 0.22rem 0.6rem !important; font-size: 0.62rem !important; }

    /* Tabs horizontales con scroll */
    .fi-tabs { overflow-x: auto; flex-wrap: nowrap !important; }
    .fi-tabs-item { white-space: nowrap; flex-shrink: 0; }
}

/* Extra small phones (< 380px): reducir aún más */
@media (max-width: 380px) {
    .fi-main, .fi-page-content { padding: 0.5rem !important; }
    .fi-header-heading { font-size: 1.15rem !important; }
    .fi-wi-stats-overview-stat-value { font-size: 1.6rem !important; }
}

/* Dark mode toggle button */
.lf-theme-toggle {
    width: 38px;
    height: 38px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(148, 163, 184, 0.1);
    border: 1px solid rgba(15, 23, 42, 0.06);
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.dark .lf-theme-toggle {
    background: rgba(148, 163, 184, 0.08);
    border-color: rgba(148, 163, 184, 0.15);
    color: #cbd5e1;
}
.lf-theme-toggle:hover {
    background: rgba(6, 182, 212, 0.12);
    color: var(--lf-accent);
    transform: scale(1.05);
}
.lf-theme-toggle .moon { display: block; }
.lf-theme-toggle .sun { display: none; }
.dark .lf-theme-toggle .moon { display: none; }
.dark .lf-theme-toggle .sun { display: block; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const insertToggle = () => {
        if (document.querySelector('.lf-theme-toggle')) return;
        // Buscar el contenedor flex del topbar end (donde está el avatar)
        const avatar = document.querySelector('.fi-topbar .fi-user-menu') ||
                       document.querySelector('.fi-topbar [x-data*="dropdown"]') ||
                       document.querySelector('.fi-topbar .fi-dropdown');
        if (!avatar) return;
        const container = avatar.parentElement;
        if (!container) return;
        // Forzar que el contenedor sea flex horizontal
        container.style.display = 'flex';
        container.style.flexDirection = 'row';
        container.style.alignItems = 'center';
        container.style.gap = '0.5rem';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'lf-theme-toggle';
        btn.title = 'Cambiar tema';
        btn.innerHTML = `
            <svg class="moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
            <svg class="sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            </svg>
        `;
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            try {
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                if (window.Alpine?.store?.('theme')) {
                    window.Alpine.store('theme').toggle?.();
                }
            } catch (e) {}
        });

        container.insertBefore(btn, avatar);
    };

    // Aplicar tema guardado al cargar
    try {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') document.documentElement.classList.add('dark');
        else if (saved === 'light') document.documentElement.classList.remove('dark');
    } catch (e) {}

    insertToggle();
    // Reintentar para Livewire navegación
    setTimeout(insertToggle, 300);
    setTimeout(insertToggle, 1000);
    document.addEventListener('livewire:navigated', insertToggle);
});
</script>
