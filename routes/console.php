<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cumpleaños del día — corre cada mañana a las 9:00
Schedule::command('birthdays:notify')->dailyAt('09:00');

// Backups de todas las BDs (central + tenants) — cada noche a las 2:00 AM
Schedule::command('backup:tenants --keep=7')->dailyAt('02:00');

// Recordatorios "te faltan sellos" — cada lunes 10 AM
Schedule::command('reminders:stamps')->weeklyOn(1, '10:00');

// Reactivación de clientes dormidos — cada lunes 11 AM
Schedule::command('reminders:reactivation')->weeklyOn(1, '11:00');
