<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * Hace mysqldump de la BD central + cada BD de tenant a storage/app/backups/{fecha}.
 * Conserva los últimos N días (default 7) y borra los más viejos.
 *
 * Programado en routes/console.php para correr cada noche.
 */
class TenantBackupCommand extends Command
{
    protected $signature = 'backup:tenants {--keep=7 : Días de respaldo a conservar}';
    protected $description = 'Respalda la BD central y todas las BDs de tenants';

    public function handle(): int
    {
        $date = now()->format('Y-m-d_His');
        $dir = storage_path("app/backups/{$date}");
        File::ensureDirectoryExists($dir);

        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');

        $databases = [config('database.connections.mysql.database')];
        foreach (Tenant::all() as $tenant) {
            $databases[] = 'tenant_'.$tenant->id;
        }

        $ok = 0;
        $fail = 0;

        foreach ($databases as $db) {
            $file = "{$dir}/{$db}.sql";

            $args = [
                $this->locateMysqldump(),
                "--host={$host}",
                "--port={$port}",
                "--user={$user}",
            ];
            if ($pass !== '') {
                $args[] = "--password={$pass}";
            }
            $args[] = '--single-transaction';
            $args[] = '--no-tablespaces';
            $args[] = '--skip-lock-tables';
            $args[] = $db;

            $process = new Process($args);
            $process->setTimeout(600);
            $process->run();

            if ($process->isSuccessful()) {
                File::put($file, $process->getOutput());
                $size = File::size($file);
                $this->info("✓ {$db} ({$this->formatBytes($size)})");
                $ok++;
            } else {
                $this->error("✗ {$db}: ".trim($process->getErrorOutput()));
                $fail++;
            }
        }

        $this->cleanupOld($this->option('keep'));

        $this->info("Respaldo terminado: {$ok} ok, {$fail} fallidos en {$dir}");

        return $fail > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function locateMysqldump(): string
    {
        // En Laragon viene en C:\laragon\bin\mysql\...\bin\mysqldump.exe
        // En Linux suele estar en el PATH
        return PHP_OS_FAMILY === 'Windows' ? 'mysqldump.exe' : 'mysqldump';
    }

    protected function cleanupOld(int $keepDays): void
    {
        $root = storage_path('app/backups');
        if (! File::isDirectory($root)) {
            return;
        }

        $cutoff = now()->subDays($keepDays);
        foreach (File::directories($root) as $dir) {
            $name = basename($dir);
            // Formato: 2026-04-06_140530
            if (! preg_match('/^(\d{4}-\d{2}-\d{2})_/', $name, $m)) {
                continue;
            }
            try {
                $date = \Carbon\Carbon::parse($m[1]);
                if ($date->lt($cutoff)) {
                    File::deleteDirectory($dir);
                    $this->line("  Eliminado backup antiguo: {$name}");
                }
            } catch (\Throwable $e) {
                continue;
            }
        }
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1).' '.$units[$i];
    }
}
