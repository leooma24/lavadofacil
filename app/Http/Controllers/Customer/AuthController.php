<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string'],
            'pin' => ['required', 'string', 'size:4'],
        ]);

        $phone = preg_replace('/\D/', '', $data['phone']);

        $customer = Customer::where('phone', $phone)
            ->orWhere('phone', 'like', "%{$phone}")
            ->first();

        if (! $customer) {
            return back()
                ->withErrors(['phone' => 'No encontramos tu número. Pídele al lavado que te registre.'])
                ->withInput();
        }

        $expectedPin = $customer->pin_code ?: substr($customer->phone, -4);

        if ($data['pin'] !== $expectedPin) {
            return back()
                ->withErrors(['pin' => 'PIN incorrecto. Por defecto son los últimos 4 dígitos de tu teléfono.'])
                ->withInput();
        }

        Auth::guard('customer')->login($customer, true);
        $request->session()->regenerate();

        return redirect()->route('customer.home');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
