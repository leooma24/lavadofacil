# LavadoFácil — Project Context for Claude

## Quick start

LavadoFácil is a multitenant SaaS for car wash loyalty programs. Each car wash is a tenant with its own database. Built by Omar Lerma as part of his SaaS suite (RentaFácil, DocFácil, LavadoFácil).

**Stack:** Laravel 12 + PHP 8.2 + Filament 3.3 + Livewire 3 + stancl/tenancy 3.x + MySQL

## URLs

| Context | URL local | URL prod (futuro) |
|---------|-----------|-------------------|
| Landing pública | `lavadofacil.test` | `lavadofacil.tu-app.co` |
| SuperAdmin | `lavadofacil.test/central` | `lavadofacil.tu-app.co/central` |
| PWA cliente | `{slug}.lavadofacil.test` | `{slug}.lavadofacil.tu-app.co` |
| Panel dueño | `{slug}.lavadofacil.test/admin` | `{slug}.lavadofacil.tu-app.co/admin` |

## Credenciales seed

| Panel | URL | Email | Password |
|-------|-----|-------|----------|
| SuperAdmin | `/central` | admin@lavadofacil.com | password |
| Dueño tenant demo | `lavadodemo.lavadofacil.test/admin` | leooma24@gmail.com | password |

## Decisiones críticas (NO cambiar sin pedir)

1. **WhatsApp = wa.me MANUAL** (NO Meta Cloud API). Usa `App\Services\WhatsAppLinkBuilder`. Botones en Filament abren WhatsApp Web con mensaje pre-llenado vía `Action::make()->url(...)->openUrlInNewTab()`. Audit en `whatsapp_messages` cuando dueño marca "Enviado".
2. **Plantillas para todo:** tabla `message_templates` con `channel ENUM('whatsapp','email')`. Editables desde admin.
3. **Solo local primero:** validar todo en `lavadofacil.test` antes de desplegar a `lavadofacil.tu-app.co`.
4. **Tenant ID = slug** (no UUID). Por eso `Tenant::getIncrementing()` retorna `false`. NO cambiar a UUID.
5. **Nombre de BD del tenant:** `tenant_{slug}` (ej: `tenant_lavadodemo`). Configurado en `config/tenancy.php` con prefix `tenant_`.

## Estructura clave

```
app/
├── Filament/
│   ├── Central/Resources/        ← Resources del panel /central (TenantResource, PlanResource)
│   └── Resources/                 ← Resources del panel /admin del tenant (CustomerResource)
├── Models/
│   ├── Tenant.php                 ← Modelo central del tenant (extiende stancl)
│   ├── Plan.php, Subscription.php, Invoice.php, CentralUser.php
│   ├── User.php                   ← User del tenant (vive en BD del tenant)
│   └── Tenant/                    ← 25 modelos del tenant (Customer, Visit, LoyaltyCard, etc)
├── Providers/
│   ├── Filament/
│   │   ├── CentralPanelProvider.php   ← /central, guard 'central'
│   │   └── AdminPanelProvider.php     ← /admin con tenancy middleware
│   └── TenancyServiceProvider.php     ← Auto-crea BD + migra + seed al crear tenant
└── Services/
    └── WhatsAppLinkBuilder.php    ← Helper para wa.me URLs

database/
├── migrations/                    ← BD CENTRAL (tenants, plans, subscriptions, invoices, central_users)
├── migrations/tenant/             ← BD POR TENANT (~17 archivos, 25+ tablas)
└── seeders/
    ├── PlanSeeder.php             ← 3 planes: Básico/Pro/Elite
    ├── CentralUserSeeder.php      ← admin@lavadofacil.com
    ├── DemoTenantSeeder.php       ← Crea tenant lavadodemo
    └── tenant/
        └── TenantInitialDataSeeder.php  ← Niveles, premios, servicios, 20 plantillas

config/
├── tenancy.php                    ← central_domains, prefix tenant_, custom Tenant model
└── auth.php                       ← guards: web (tenant) + central
```

## Comandos comunes

```bash
# Setup local
mysql -u root -e "CREATE DATABASE lavadofacil_central"
php artisan migrate --force
php artisan db:seed --force

# Limpiar y resetear
mysql -u root -e "DROP DATABASE lavadofacil_central; CREATE DATABASE lavadofacil_central"
mysql -u root -e "SHOW DATABASES LIKE 'tenant%'" | tail -n +2 | xargs -I {} mysql -u root -e "DROP DATABASE {}"
php artisan migrate --seed --force

# Listar tenants
php artisan tenants:list

# Migrar tenants existentes
php artisan tenants:migrate

# Crear tenant manualmente desde tinker
php artisan tinker
> $t = App\Models\Tenant::create(['id' => 'lavadoabc', 'slug' => 'lavadoabc', 'name' => 'Lavado ABC', ...]);
> $t->domains()->create(['domain' => 'lavadoabc.lavadofacil.test']);
```

## Features completas (a implementar en fases siguientes)

Esquema y modelos completos para:
1. ✅ Tarjeta de 8 sellos con ruleta de premios (probabilidad ponderada)
2. ✅ Rifa mensual (tickets por visita)
3. ✅ Niveles Bronce/Plata/Oro/Platino (configurables)
4. ✅ Referidos con código único
5. ✅ Cumpleaños automático
6. ✅ Racha de visitas
7. ✅ WhatsApp manual vía wa.me con plantillas
8. ✅ Reactivación de clientes dormidos
9. ✅ Ranking mensual
10. ✅ Reto mensual configurable
11. ✅ Encuesta post-visita
12. ✅ Suscripción VIP
13. ✅ Paquetes prepago
14. ✅ Daily stats para predicción de ganancias

**Estado actual:** Arquitectura base + schemas + modelos + Filament panels + 3 Resources básicos (TenantResource, PlanResource, CustomerResource) + seeders funcionando end-to-end. Las features arriba están en BD y modelos pero falta UI/lógica.

## Servidor de producción (cuando se despliegue)

- **SSH:** `ssh tuapp` (alias en `~/.ssh/config`)
- **Path:** `/var/www/lavadofacil` (a crear)
- **Repo:** https://github.com/leooma24/lavadofacil.git
- Mismo VPS que DocFácil/RentaFácil

**Importante:** El usuario explícitamente pidió NO desplegar todavía. Validar todo en local primero.
