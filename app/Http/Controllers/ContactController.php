<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot anti-bot
        if ($request->filled('website_url')) {
            return back()->with('contact_success', true);
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:180',
            'phone' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:80',
            'business_name' => 'nullable|string|max:120',
            'message' => 'nullable|string|max:2000',
        ]);

        $lead = DB::table('contact_leads')->insertGetId(array_merge($data, [
            'source' => 'landing',
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        // Notificar por email (best-effort, no rompe la respuesta si falla)
        try {
            $body = "Nuevo lead de LavadoFácil:\n\n"
                . "Nombre: {$data['name']}\n"
                . "Email: {$data['email']}\n"
                . "WhatsApp: " . ($data['phone'] ?? '-') . "\n"
                . "Ciudad: " . ($data['city'] ?? '-') . "\n"
                . "Car wash: " . ($data['business_name'] ?? '-') . "\n"
                . "Mensaje:\n" . ($data['message'] ?? '-') . "\n\n"
                . "Lead #{$lead} | IP: {$request->ip()}";

            Mail::raw($body, function ($m) use ($data) {
                $m->to('leooma24@gmail.com')
                    ->subject('🚗 Nuevo lead LavadoFácil: ' . $data['name'])
                    ->replyTo($data['email'], $data['name']);
            });
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('contact_success', true)->withFragment('contacto');
    }
}
