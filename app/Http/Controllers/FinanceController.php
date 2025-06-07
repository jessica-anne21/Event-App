<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    public function pendingPayments()
    {
        if (Auth::user()->role !== 'finance') {
            abort(403, 'Anda tidak memiliki akses sebagai Tim Keuangan.');
        }

        $pendingRegistrations = Registration::where('payment_status', 'pending')
                                            ->whereNotNull('payment_proof_path')
                                            ->with('user', 'event')
                                            ->get();
        return view('finance.pending_payments', compact('pendingRegistrations'));
    }

    public function verifyPayment(Registration $registration)
    {
        if (Auth::user()->role !== 'finance') {
            abort(403, 'Anda tidak memiliki akses sebagai Tim Keuangan.');
        }

        $registration->update(['payment_status' => 'paid']);
        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment(Registration $registration)
    {
        if (Auth::user()->role !== 'finance') {
            abort(403, 'Anda tidak memiliki akses sebagai Tim Keuangan.');
        }

        // Opsional: Hapus bukti pembayaran jika ditolak, atau berikan catatan
        // if ($registration->payment_proof_path) {
        //     Storage::disk('public')->delete($registration->payment_proof_path);
        // }
        $registration->update(['payment_status' => 'pending', 'payment_proof_path' => null]);
        return redirect()->back()->with('success', 'Pembayaran ditolak. Peserta perlu mengunggah ulang bukti pembayaran.');
    }

    // --- Metode Baru untuk History Pembayaran ---
    public function verifiedPayments()
    {
        if (Auth::user()->role !== 'finance') {
            abort(403, 'Anda tidak memiliki akses sebagai Tim Keuangan.');
        }

        // Ambil semua registrasi yang statusnya 'paid'
        $verifiedRegistrations = Registration::where('payment_status', 'paid')
                                            ->with('user', 'event')
                                            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan waktu verifikasi terbaru
                                            ->get();

        return view('finance.verified_payments', compact('verifiedRegistrations'));
    }
}