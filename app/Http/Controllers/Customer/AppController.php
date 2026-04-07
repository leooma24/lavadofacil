<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Appointment;
use App\Models\Tenant\Customer;
use App\Models\Tenant\PrizeSpin;
use App\Models\Tenant\Raffle;
use App\Models\Tenant\RaffleTicket;
use App\Models\Tenant\Service;
use App\Models\Tenant\Survey;
use App\Models\Tenant\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function home()
    {
        $customer = Auth::guard('customer')->user();
        $customer->loadMissing('loyaltyCard');

        $monthStart = now()->startOfMonth();

        $visitsMonth = $customer->visits()->where('visited_at', '>=', $monthStart)->count();
        $spentMonth = (float) $customer->visits()->where('visited_at', '>=', $monthStart)->sum('total');

        $raffle = Raffle::where('month', now()->month)
            ->where('year', now()->year)
            ->where('status', 'active')
            ->first();

        $raffleTickets = $raffle
            ? RaffleTicket::where('raffle_id', $raffle->id)->where('customer_id', $customer->id)->count()
            : 0;

        $stamps = $customer->loyaltyCard?->stamps_count ?? 0;
        $stampsTotal = 8;
        $stampsLeft = max(0, $stampsTotal - $stamps);

        // ¿Tiene un premio recién ganado sin reclamar para mostrar la ruleta?
        $unclaimedSpin = PrizeSpin::with('prize')
            ->where('customer_id', $customer->id)
            ->whereNull('claimed_at')
            ->where('spun_at', '>=', now()->subDays(30))
            ->orderByDesc('spun_at')
            ->first();

        // ¿Tiene una visita reciente sin encuesta?
        $pendingSurveyVisit = Visit::where('customer_id', $customer->id)
            ->where('visited_at', '>=', now()->subDays(7))
            ->whereDoesntHave('survey')
            ->orderByDesc('visited_at')
            ->first();

        // ¿Tiene una cita lista (el dueño le avisó "ya puedes traerlo")?
        $readyAppointment = Appointment::where('customer_id', $customer->id)
            ->whereNotNull('ready_notified_at')
            ->whereNull('customer_response')
            ->whereIn('status', ['confirmed', 'in_queue'])
            ->latest('ready_notified_at')
            ->first();

        return view('customer.pages.home', compact(
            'customer', 'visitsMonth', 'spentMonth', 'raffle', 'raffleTickets',
            'stamps', 'stampsTotal', 'stampsLeft', 'unclaimedSpin', 'pendingSurveyVisit',
            'readyAppointment',
        ));
    }

    public function catalog()
    {
        $customer = Auth::guard('customer')->user();
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        return view('customer.pages.catalog', compact('customer', 'services'));
    }

    public function showService(Service $service)
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.pages.service-detail', compact('customer', 'service'));
    }

    public function bookService(Request $request, Service $service)
    {
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'type' => 'required|in:in_shop,at_home',
            'address' => 'nullable|required_if:type,at_home|string|max:255',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        Appointment::create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'type' => $data['type'],
            'address' => $data['address'] ?? null,
            'scheduled_at' => $data['scheduled_at'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('customer.appointments')->with('booking_success', true);
    }

    public function appointments()
    {
        $customer = Auth::guard('customer')->user();
        $appointments = Appointment::where('customer_id', $customer->id)
            ->with('service')
            ->orderByDesc('scheduled_at')
            ->get();

        return view('customer.pages.appointments', compact('customer', 'appointments'));
    }

    public function respondAppointment(Request $request, Appointment $appointment)
    {
        $customer = Auth::guard('customer')->user();
        abort_unless($appointment->customer_id === $customer->id, 403);

        $data = $request->validate([
            'response' => 'required|in:going,cant_make_it',
        ]);

        $appointment->update([
            'customer_response' => $data['response'],
            'responded_at' => now(),
            'status' => $data['response'] === 'cant_make_it' ? 'cancelled' : $appointment->status,
        ]);

        return redirect()->route('customer.home');
    }

    public function visits()
    {
        $customer = Auth::guard('customer')->user();
        $visits = $customer->visits()
            ->with('services')
            ->orderByDesc('visited_at')
            ->paginate(20);

        return view('customer.pages.visits', compact('customer', 'visits'));
    }

    public function prizes()
    {
        $customer = Auth::guard('customer')->user();
        $spins = PrizeSpin::with('prize')
            ->where('customer_id', $customer->id)
            ->orderByDesc('spun_at')
            ->get();

        return view('customer.pages.prizes', compact('customer', 'spins'));
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        $currentLevel = \App\Models\Tenant\LevelConfig::where('level', $customer->level)->first();
        $nextLevel = \App\Models\Tenant\LevelConfig::where('sort_order', '>', $currentLevel?->sort_order ?? 0)
            ->orderBy('sort_order')
            ->first();
        return view('customer.pages.profile', compact('customer', 'currentLevel', 'nextLevel'));
    }

    public function ranking()
    {
        $customer = Auth::guard('customer')->user();
        $monthStart = now()->startOfMonth();

        $top = Customer::query()
            ->select('customers.id', 'customers.name', 'customers.level')
            ->selectSub(
                Visit::selectRaw('COUNT(*)')
                    ->whereColumn('customer_id', 'customers.id')
                    ->where('visited_at', '>=', $monthStart),
                'visits_month',
            )
            ->selectSub(
                Visit::selectRaw('COALESCE(SUM(total),0)')
                    ->whereColumn('customer_id', 'customers.id')
                    ->where('visited_at', '>=', $monthStart),
                'spent_month',
            )
            ->whereHas('visits', fn ($q) => $q->where('visited_at', '>=', $monthStart))
            ->orderByDesc('visits_month')
            ->limit(10)
            ->get();

        // Posición del cliente actual
        $myPosition = null;
        foreach ($top as $i => $row) {
            if ($row->id === $customer->id) {
                $myPosition = $i + 1;
                break;
            }
        }

        return view('customer.pages.ranking', compact('customer', 'top', 'myPosition'));
    }

    public function showSurvey(Visit $visit)
    {
        $customer = Auth::guard('customer')->user();
        abort_unless($visit->customer_id === $customer->id, 403);
        abort_if($visit->survey()->exists(), 404);

        return view('customer.pages.survey', compact('customer', 'visit'));
    }

    public function storeSurvey(Request $request, Visit $visit)
    {
        $customer = Auth::guard('customer')->user();
        abort_unless($visit->customer_id === $customer->id, 403);

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'nps' => 'nullable|integer|min:0|max:10',
            'comments' => 'nullable|string|max:500',
        ]);

        Survey::create([
            'visit_id' => $visit->id,
            'customer_id' => $customer->id,
            'rating' => $data['rating'],
            'nps' => $data['nps'] ?? null,
            'comments' => $data['comments'] ?? null,
            'would_recommend' => ($data['nps'] ?? 10) >= 7,
            'answered_at' => now(),
        ]);

        // Alerta inmediata al dueño si la calificación es baja
        if ($data['rating'] <= 2) {
            \App\Models\Tenant\WhatsappMessage::create([
                'customer_id' => $customer->id,
                'sent_by_user_id' => \App\Models\User::where('role', 'owner')->value('id') ?? 1,
                'type' => 'low_rating_alert',
                'phone' => $customer->phone,
                'body' => "⚠️ ALERTA: {$customer->name} calificó su visita con {$data['rating']} estrellas. Comentario: ".($data['comments'] ?? '(sin comentario)'),
                'sent_at' => null,
                'notes' => 'Alerta de baja calificación — contactar al cliente',
            ]);
        }

        return redirect()->route('customer.home')->with('survey_thanks', true);
    }
}
