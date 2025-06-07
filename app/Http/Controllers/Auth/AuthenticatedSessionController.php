<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter; // <--- Pastikan ini ada
use Illuminate\Support\Str;                 // <--- Pastikan ini ada
use Illuminate\Validation\ValidationException; // <--- Pastikan ini ada
use Illuminate\View\View;
use App\Models\User; // <--- Pastikan ini ada untuk mengakses model User

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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Pastikan tidak melebihi batas percobaan login (rate limiting)
        $this->ensureIsNotRateLimited($request);

        // --- Perubahan Utama: Pengecekan Kredensial dan is_active ---
        // Mencoba otentikasi hanya dengan email dan password
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request)); // Hit rate limiter jika gagal

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'), // Pesan error standar jika kredensial salah
            ]);
        }

        // Dapatkan user yang baru saja berhasil terautentikasi (email dan password benar)
        $user = Auth::user();

        // Pengecekan tambahan: Apakah akun aktif?
        if (! $user->is_active) {
            Auth::logout(); // Logout user jika sudah terautentikasi tapi tidak aktif
            RateLimiter::hit($this->throttleKey($request)); // Hit rate limiter untuk akun dinonaktifkan
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.', // Pesan error kustom
            ]);
        }
        // --- Akhir Perubahan Utama ---

        $request->session()->regenerate(); // Regenerasi session ID untuk keamanan
        RateLimiter::clear($this->throttleKey($request)); // Bersihkan rate limiter jika berhasil login

        // Logika redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.users.index'));
        } elseif ($user->role === 'finance') {
            return redirect()->intended(route('finance.payments.pending'));
        } elseif ($user->role === 'committee') {
            return redirect()->intended(route('committee.events.index'));
        } else {
            return redirect()->intended(route('events.index')); // Member dan Guest
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        // event(new Lockout($this)); // Ini mungkin memerlukan impor Lockout, jika tidak ada, bisa diabaikan atau ditambahkan

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
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