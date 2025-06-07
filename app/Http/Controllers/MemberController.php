<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\SubEvent; // Pastikan ini ada
use App\Models\SessionRegistration; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Untuk generate kode unik
use SimpleSoftwareIO\QrCode\Facades\QrCode; // Install ini: composer require simplesoftwareio/simple-qrcode
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function registerEventForm(Event $event)
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }

        $existingRegistration = Auth::user()->registrations()->where('event_id', $event->id)->first();
        if ($existingRegistration) {
            return redirect()->route('member.my_registrations')->with('info', 'Anda sudah terdaftar di event ini.');
        }

        return view('member.register_event', compact('event'));
    }

    public function registerEvent(Request $request, Event $event)
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }

        if ($event->max_participants && $event->registrations()->where('payment_status', 'paid')->count() >= $event->max_participants) {
            return redirect()->back()->with('error', 'Kuota event sudah penuh.');
        }

        $registrationCode = Str::random(10) . time();

        Registration::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'registration_code' => $registrationCode,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('member.my_registrations')->with('success', 'Pendaftaran event berhasil! Silakan lakukan pembayaran.');
    }

    public function myRegistrations()
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        $registrations = Auth::user()->registrations()->with('event')->get();
        return view('member.my_registrations', compact('registrations'));
    }

    public function uploadPaymentProof(Request $request, Registration $registration)
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) { // Pastikan user hanya bisa upload bukti untuk registrasinya sendiri
            abort(403, 'Anda tidak berhak mengunggah bukti pembayaran ini.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $registration->update([
                'payment_proof_path' => $path,
                'payment_status' => 'pending',
            ]);
            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi.');
        }
        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    public function showQrCode(Registration $registration)
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }
        if ($registration->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Pembayaran Anda belum terverifikasi.');
        }

        $qrCode = QrCode::size(300)->generate($registration->registration_code);
        return view('member.show_qrcode', compact('qrCode', 'registration'));
    }

    public function downloadCertificate(Registration $registration)
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }
        if (!$registration->attended || !$registration->certificate_path) {
            return redirect()->back()->with('error', 'Sertifikat belum tersedia atau Anda tidak tercatat hadir.');
        }

        $path = storage_path('app/public/' . $registration->certificate_path);
        if (file_exists($path)) {
            return response()->download($path);
        }
        return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
    }

    // --- METODE BARU YANG HARUS DITAMBAHKAN ---
    public function selectSessionsForm(Registration $registration)
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) { // Pastikan member hanya bisa memilih sesi untuk registrasinya sendiri
            abort(403, 'Anda tidak berhak mengelola sesi registrasi ini.');
        }
        if ($registration->payment_status !== 'paid') {
            return redirect()->route('member.my_registrations')->with('error', 'Silakan lunasi pembayaran event utama Anda terlebih dahulu sebelum memilih sesi.');
        }

        $event = $registration->event;
        $availableSessions = $event->subEvents()->orderBy('date')->orderBy('start_time')->get();
        $selectedSessionIds = $registration->sessionRegistrations->pluck('sub_event_id')->toArray();

        return view('member.select_sessions', compact('registration', 'event', 'availableSessions', 'selectedSessionIds'));
    }

    public function storeSelectedSessions(Request $request, Registration $registration)
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) { // Pastikan member hanya bisa memilih sesi untuk registrasinya sendiri
            abort(403, 'Anda tidak berhak mengelola sesi registrasi ini.');
        }
        if ($registration->payment_status !== 'paid') {
            return redirect()->route('member.my_registrations')->with('error', 'Silakan lunasi pembayaran event utama Anda terlebih dahulu sebelum memilih sesi.');
        }

        $request->validate([
            'sessions' => 'nullable|array',
            'sessions.*' => 'exists:sub_events,id',
        ]);

        $selectedSessionIds = $request->input('sessions', []);

        // Hapus sesi yang tidak lagi dipilih
        $registration->sessionRegistrations()->whereNotIn('sub_event_id', $selectedSessionIds)->delete();

        foreach ($selectedSessionIds as $subEventId) {
            $subEvent = SubEvent::find($subEventId);

            // Periksa apakah sesi valid dan milik event yang sama
            if (!$subEvent || $subEvent->event_id !== $registration->event_id) {
                continue; // Lewati sesi yang tidak valid atau bukan milik event ini
            }

            // Cek kuota sesi sebelum menambahkan
            if ($subEvent->max_participants && $subEvent->sessionRegistrations()->count() >= $subEvent->max_participants) {
                // Jika sesi penuh dan user belum terdaftar di sesi itu
                if (!SessionRegistration::where('registration_id', $registration->id)->where('sub_event_id', $subEventId)->exists()) {
                     return redirect()->back()->with('error', 'Sesi "' . $subEvent->name . '" sudah penuh. Silakan pilih sesi lain.');
                }
            }

            // Tambahkan sesi yang baru dipilih jika belum ada
            SessionRegistration::firstOrCreate([
                'registration_id' => $registration->id,
                'sub_event_id' => $subEventId,
            ]);
        }

        return redirect()->route('member.my_registrations')->with('success', 'Pilihan sesi Anda berhasil diperbarui.');
    }
}