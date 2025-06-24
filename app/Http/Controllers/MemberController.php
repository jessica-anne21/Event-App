<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\SubEvent;
use App\Models\SessionRegistration;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function registerEventForm(Event $event)
    {
        if (Auth::user()->role !== 'member') {
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
        if (Auth::user()->role !== 'member') {
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

    // --- METODE YANG HILANG ATAU SALAH myRegistrations() ---
    public function myRegistrations()
    {
        if (Auth::user()->role !== 'member') { // Pengecekan role
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        $registrations = Auth::user()->registrations()->with('event', 'sessionRegistrations.subEvent')->get(); // Load relasi sesi juga
        return view('member.my_registrations', compact('registrations'));
    }
    // --- AKHIR METODE myRegistrations() ---

    public function uploadPaymentProof(Request $request, Registration $registration)
    {
        if (Auth::user()->role !== 'member') {
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) {
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
        if (Auth::user()->role !== 'member') { abort(403, 'Anda tidak memiliki akses sebagai Member.'); }
        if ($registration->user_id !== Auth::id()) { abort(403); }
        if ($registration->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Pembayaran Anda belum terverifikasi.');
        }

        // --- Penggunaan chillerlan/php-qrcode ---
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L, // <--- UBAH INI: Gunakan ECC_L (Level Low)
            'scale'      => 10,
            'imageBase64' => false,
        ]);

        $qrcode = new QRCode($options);
        $qrCodeBinaryData = $qrcode->render($registration->registration_code); // Ini menghasilkan binary data gambar PNG

        $qrCodeFileName = 'qr_' . $registration->registration_code . '.png';
        $qrCodePath = 'qrcodes/' . $qrCodeFileName;

        // Simpan binary data gambar ke storage
        Storage::disk('public')->put($qrCodePath, $qrCodeBinaryData);
        // --- Akhir penggunaan chillerlan/php-qrcode ---

        return view('member.show_qrcode', compact('qrCodePath', 'registration'));
    }


    public function downloadCertificate(Registration $registration)
    {
        if (Auth::user()->role !== 'member') {
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

    public function selectSessionsForm(Registration $registration)
    {
        if (Auth::user()->role !== 'member') {
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengelola sesi registrasi ini.');
        }
        if ($registration->payment_status !== 'paid') {
            return redirect()->route('member.my_registrations')->with('error', 'Silakan lunasi pembayaran event utama Anda terlebih dahulu sebelum memilih sesi.');
        }

        $event = $registration->event;
        $availableSessions = $event->subEvents()->withCount('sessionRegistrations')->orderBy('date')->orderBy('start_time')->get();
        $selectedSessionIds = $registration->sessionRegistrations->pluck('sub_event_id')->toArray();

        return view('member.select_sessions', compact('registration', 'event', 'availableSessions', 'selectedSessionIds'));
    }

    public function storeSelectedSessions(Request $request, Registration $registration)
    {
        if (Auth::user()->role !== 'member') {
            abort(403, 'Anda tidak memiliki akses sebagai Member.');
        }
        if ($registration->user_id !== Auth::id()) {
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

        $currentSessionRegistrations = $registration->sessionRegistrations->pluck('sub_event_id')->toArray();

        $sessionsToRemove = array_diff($currentSessionRegistrations, $selectedSessionIds);
        if (!empty($sessionsToRemove)) {
            $registration->sessionRegistrations()->whereIn('sub_event_id', $sessionsToRemove)->delete();
        }

        $sessionsAdded = [];
        foreach ($selectedSessionIds as $subEventId) {
            if (!in_array($subEventId, $currentSessionRegistrations)) {
                $subEvent = SubEvent::withCount('sessionRegistrations')->find($subEventId);

                if (!$subEvent || $subEvent->event_id !== $registration->event_id) {
                    continue;
                }

                if ($subEvent->max_participants !== null && $subEvent->session_registrations_count >= $subEvent->max_participants) {
                    if (!SessionRegistration::where('registration_id', $registration->id)->where('sub_event_id', $subEventId)->exists()) {
                         return redirect()->back()->with('error', 'Sesi "' . $subEvent->name . '" sudah penuh. Silakan batalkan pilihan Anda atau pilih sesi lain.');
                    }
                }

                SessionRegistration::create([
                    'registration_id' => $registration->id,
                    'sub_event_id' => $subEventId,
                ]);
                $sessionsAdded[] = $subEvent->name;
            }
        }

        $message = 'Pilihan sesi Anda berhasil diperbarui.';
        if (!empty($sessionsAdded)) {
            $message .= ' Sesi baru ditambahkan: ' . implode(', ', $sessionsAdded) . '.';
        }

        return redirect()->route('member.my_registrations')->with('success', $message);
    }
}