<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Tenant\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushController extends Controller
{
    public function vapidKey()
    {
        return response()->json(['key' => config('webpush.vapid.public_key')]);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $customer = Auth::guard('customer')->user();

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'customer_id' => $customer->id,
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
            ],
        );

        return response()->json(['ok' => true]);
    }

    public function unsubscribe(Request $request)
    {
        PushSubscription::where('endpoint', $request->endpoint)->delete();
        return response()->json(['ok' => true]);
    }
}
