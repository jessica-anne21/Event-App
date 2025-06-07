<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Dapatkan user yang baru login
        $user = Auth::user();

        // Logika redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.users.index')); // Arahkan admin ke halaman manajemen user admin
        } elseif ($user->role === 'finance') {
            return redirect()->intended(route('finance.payments.pending')); // Arahkan finance ke halaman verifikasi pembayaran
        } elseif ($user->role === 'committee') { // <-- TAMBAHKAN KONDISI INI
            return redirect()->intended(route('committee.events.index')); // Arahkan committee ke halaman manajemen event mereka
        } else {
            return redirect()->intended(route('events.index')); // Arahkan role lain (member, guest) ke halaman daftar event publik
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}