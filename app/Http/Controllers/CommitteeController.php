<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\SubEvent; // Pastikan ini ada
use App\Models\SessionRegistration; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CommitteeController extends Controller
{
    // A. Manajemen Event
    public function indexEvents()
    {
        // --- Pengecekan Peran ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        $events = Event::where('created_by', Auth::id())->orderBy('date', 'desc')->get();
        return view('committee.events.index', compact('events'));
    }

    public function createEventForm()
    {
        // --- Pengecekan Peran ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        return view('committee.events.create');
    }

    public function storeEvent(Request $request)
    {
        // --- Pengecekan Peran ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'registration_fee' => 'required|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('event_posters', 'public');
        }

        Event::create([
            'name' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'speaker' => $request->speaker,
            'poster_path' => $posterPath,
            'registration_fee' => $request->registration_fee,
            'max_participants' => $request->max_participants,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('committee.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function editEventForm(Event $event)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($event->created_by !== Auth::id()) {
            abort(403);
        }
        return view('committee.events.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($event->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'registration_fee' => 'required|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $posterPath = $event->poster_path;
        if ($request->hasFile('poster')) {
            if ($posterPath) {
                Storage::disk('public')->delete($posterPath);
            }
            $posterPath = $request->file('poster')->store('event_posters', 'public');
        }

        $event->update([
            'name' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'speaker' => $request->speaker,
            'poster_path' => $posterPath,
            'registration_fee' => $request->registration_fee,
            'max_participants' => $request->max_participants,
            'description' => $request->description,
        ]);

        return redirect()->route('committee.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function deleteEvent(Event $event)
    {
        // Pengecekan Peran (sudah ada)
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }

        // Pengecekan Kepemilikan Event (sudah ada)
        if ($event->created_by !== Auth::id()) {
            abort(403);
        }

        // --- PERUBAHAN DI SINI: Trigger jika sudah ada yang register ---
        // Load relasi registrations untuk menghitung
        $event->loadCount('registrations'); // Menambahkan `registrations_count` ke objek $event

        if ($event->registrations_count > 0) {
            return redirect()->back()->with('error', 'Event tidak dapat dihapus karena sudah ada ' . $event->registrations_count . ' peserta terdaftar. Silakan hubungi admin untuk bantuan.');
        }
        // --- AKHIR PERUBAHAN ---

        // Hapus poster jika ada
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete(); // Ini akan menghapus event utama
        // Karena ada onDelete('cascade') di migrasi, sub_events dan registrations terkait juga akan terhapus jika diizinkan.
        // Tapi di sini kita mencegahnya jika sudah ada registrations.

        return redirect()->route('committee.events.index')->with('success', 'Event berhasil dihapus.');
    }

    // A. Manajemen Sub-Events/Sesi
    public function indexSubEvents(Event $event)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($event->created_by !== Auth::id()) { abort(403); }
        $subEvents = $event->subEvents()->orderBy('date')->orderBy('start_time')->get();
        return view('committee.sub_events.index', compact('event', 'subEvents'));
    }

    public function createSubEventForm(Event $event)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($event->created_by !== Auth::id()) { abort(403); }
        return view('committee.sub_events.create', compact('event'));
    }

    public function storeSubEvent(Request $request, Event $event)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($event->created_by !== Auth::id()) { abort(403); }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        $event->subEvents()->create($request->all());

        return redirect()->route('committee.events.sub_events.index', $event)->with('success', 'Sesi berhasil ditambahkan.');
    }

    public function editSubEventForm(SubEvent $subEvent)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($subEvent->event->created_by !== Auth::id()) { abort(403); }
        return view('committee.sub_events.edit', compact('subEvent'));
    }

    public function updateSubEvent(Request $request, SubEvent $subEvent)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($subEvent->event->created_by !== Auth::id()) { abort(403); }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'speaker' => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        $subEvent->update($request->all());

        return redirect()->route('committee.events.sub_events.index', $subEvent->event)->with('success', 'Sesi berhasil diperbarui.');
    }

    public function deleteSubEvent(SubEvent $subEvent)
    {
        if (Auth::user()->role !== 'committee') { abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.'); }
        if ($subEvent->event->created_by !== Auth::id()) { abort(403); }
        $subEvent->delete();
        return redirect()->route('committee.events.sub_events.index', $subEvent->event)->with('success', 'Sesi berhasil dihapus.');
    }


    // B. Scan QR/Bar Code untuk Presensi Event Utama
    public function showAttendanceScanner(Event $event)
    {
        // --- TAMBAHKAN PENGECEKAN PERAN DI SINI ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        if ($event->created_by !== Auth::id()) {
            abort(403);
        }
        $registeredParticipants = Registration::where('event_id', $event->id)->with('user')->get();
        return view('committee.attendance.scanner', compact('event', 'registeredParticipants'));
    }

    public function scanAttendance(Request $request, Event $event)
    {
        // --- TAMBAHKAN PENGECEKAN PERAN DI SINI ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        if ($event->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'registration_code' => 'required|string|max:255',
        ]);

        $registration = Registration::where('event_id', $event->id)
                                    ->where('registration_code', $request->registration_code)
                                    ->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'Kode registrasi tidak valid atau tidak ditemukan untuk event ini.');
        }

        if ($registration->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Peserta ini belum melunasi pembayaran.');
        }

        if ($registration->attended) {
            return redirect()->back()->with('info', 'Peserta ini sudah tercatat hadir.');
        }

        $registration->update(['attended' => true]);
        return redirect()->back()->with('success', 'Presensi berhasil dicatat untuk: ' . $registration->user->name);
    }

    // D. Scan QR/Bar Code untuk Presensi Sesi (TAMBAHKAN INI)
    public function showSessionAttendanceScanner(Event $event)
    {
        // --- TAMBAHKAN PENGECEKAN PERAN DI SINI ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        if ($event->created_by !== Auth::id()) {
            abort(403);
        }
        $subEvents = $event->subEvents()->orderBy('date')->orderBy('start_time')->get();
        return view('committee.attendance.session_scanner', compact('event', 'subEvents'));
    }

    public function scanSessionAttendance(Request $request, Event $event)
    {
        // --- TAMBAHKAN PENGECEKAN PERAN DI SINI ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        if ($event->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'registration_code' => 'required|string|max:255',
            'sub_event_id' => 'required|exists:sub_events,id',
        ]);

        $registration = Registration::where('event_id', $event->id)
                                    ->where('registration_code', $request->registration_code)
                                    ->first();

        if (!$registration) {
            return response()->json(['status' => 'error', 'message' => 'Kode registrasi tidak valid atau tidak ditemukan untuk event ini.'], 404);
        }
        if ($registration->payment_status !== 'paid') {
            return response()->json(['status' => 'error', 'message' => 'Peserta ini belum melunasi pembayaran event utama.'], 403);
        }

        $subEvent = SubEvent::find($request->sub_event_id);
        if (!$subEvent || $subEvent->event_id !== $event->id) {
            return response()->json(['status' => 'error', 'message' => 'Sesi tidak valid untuk event ini.'], 400);
        }

        $sessionRegistration = SessionRegistration::where('registration_id', $registration->id)
                                                ->where('sub_event_id', $subEvent->id)
                                                ->first();

        if (!$sessionRegistration) {
            return response()->json(['status' => 'error', 'message' => 'Peserta ini tidak terdaftar untuk sesi "' . $subEvent->name . '".'], 400);
        }

        if ($sessionRegistration->attended_session) {
            return response()->json(['status' => 'info', 'message' => 'Peserta "' . $registration->user->name . '" sudah tercatat hadir di sesi "' . $subEvent->name . '".'], 200);
        }

        $sessionRegistration->update(['attended_session' => true]);
        return response()->json(['status' => 'success', 'message' => 'Presensi berhasil dicatat untuk "' . $registration->user->name . '" di sesi "' . $subEvent->name . '".'], 200);
    }


    // E. Upload Sertifikat Event Utama
    public function uploadCertificateForm(Event $event)
    {
        // --- TAMBAHKAN PENGECEKAN PERAN DI SINI ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        if ($event->created_by !== Auth::id()) {
            abort(403);
        }
        $attendedParticipants = Registration::where('event_id', $event->id)->where('attended', true)->with('user')->get();
        return view('committee.certificates.upload_form', compact('event', 'attendedParticipants'));
    }

    public function uploadCertificates(Request $request, Event $event)
    {
        // --- TAMBAHKAN PENGECEKAN PERAN DI SINI ---
        if (Auth::user()->role !== 'committee') {
            abort(403, 'Anda tidak memiliki akses sebagai Panitia Kegiatan.');
        }
        // --- End Pengecekan Peran ---

        if ($event->created_by !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'certificates.*' => 'required|file|mimes:pdf|max:5120', // Max 5MB per file
            'registration_ids.*' => ['required', 'exists:registrations,id', Rule::in($event->registrations()->where('attended', true)->pluck('id')->toArray())],
        ]);

        $uploadedCount = 0;
        foreach ($request->file('certificates') as $key => $file) {
            $registrationId = $request->registration_ids[$key];
            $registration = Registration::find($registrationId);

            if ($registration && $registration->event_id === $event->id && $registration->attended) {
                $path = $file->store('certificates', 'public');
                $registration->update(['certificate_path' => $path]);
                $uploadedCount++;
            }
        }

        return redirect()->back()->with('success', "Berhasil mengunggah {$uploadedCount} sertifikat.");
    }
}