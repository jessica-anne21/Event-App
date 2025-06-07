<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; // Untuk redirect login
use App\Http\Controllers\Auth\RegisteredUserController; // Untuk redirect register

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rute Publik (Guest)
Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Rute Autentikasi (menggunakan Breeze/Fortify)
// Ini adalah rute bawaan dari Breeze/Fortify yang Anda gunakan.
// Pastikan ini tetap ada agar login/register berfungsi.
require __DIR__.'/auth.php'; // Biasanya file ini berisi rute login, register, dll.

// Contoh: Rute yang hanya bisa diakses setelah login (tanpa mempedulikan role dulu)
// Pengecekan role akan dihandle di dalam controller
Route::middleware('auth')->group(function () {

    // ADMIN ROUTES
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUserForm'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUserForm'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::put('/users/{user}/toggle-active', [AdminController::class, 'toggleUserActive'])->name('users.toggle_active');
    });

    // MEMBER ROUTES
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/events/{event}/register', [MemberController::class, 'registerEventForm'])->name('events.register');
        Route::post('/events/{event}/register', [MemberController::class, 'registerEvent'])->name('events.do_register');
        Route::get('/my-registrations', [MemberController::class, 'myRegistrations'])->name('my_registrations');
        Route::post('/registrations/{registration}/upload-proof', [MemberController::class, 'uploadPaymentProof'])->name('registrations.upload_proof');
        Route::get('/registrations/{registration}/qrcode', [MemberController::class, 'showQrCode'])->name('registrations.qrcode');
        Route::get('/registrations/{registration}/certificate', [MemberController::class, 'downloadCertificate'])->name('registrations.certificate');
        Route::get('/registrations/{registration}/select-sessions', [MemberController::class, 'selectSessionsForm'])->name('registrations.select_sessions');
        Route::post('/registrations/{registration}/select-sessions', [MemberController::class, 'storeSelectedSessions'])->name('registrations.store_selected_sessions');
    });

    // FINANCE ROUTES
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/payments/pending', [FinanceController::class, 'pendingPayments'])->name('payments.pending');
        Route::put('/payments/{registration}/verify', [FinanceController::class, 'verifyPayment'])->name('payments.verify');
        Route::put('/payments/{registration}/reject', [FinanceController::class, 'rejectPayment'])->name('payments.reject');
        Route::get('/payments/verified', [FinanceController::class, 'verifiedPayments'])->name('payments.verified');
    });

    // COMMITTEE ROUTES
    Route::prefix('committee')->name('committee.')->group(function () {
        Route::get('/events', [CommitteeController::class, 'indexEvents'])->name('events.index'); // Halaman daftar event yang dibuat committee
        Route::get('/events/create', [CommitteeController::class, 'createEventForm'])->name('events.create');
        Route::post('/events', [CommitteeController::class, 'storeEvent'])->name('events.store');
        Route::get('/events/{event}/edit', [CommitteeController::class, 'editEventForm'])->name('events.edit');
        Route::put('/events/{event}', [CommitteeController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{event}', [CommitteeController::class, 'deleteEvent'])->name('events.delete');

        Route::get('/events/{event}/sub-events', [CommitteeController::class, 'indexSubEvents'])->name('events.sub_events.index');
        Route::get('/events/{event}/sub-events/create', [CommitteeController::class, 'createSubEventForm'])->name('events.sub_events.create');
        Route::post('/events/{event}/sub-events', [CommitteeController::class, 'storeSubEvent'])->name('events.sub_events.store');
        Route::get('/sub-events/{subEvent}/edit', [CommitteeController::class, 'editSubEventForm'])->name('sub_events.edit');
        Route::put('/sub-events/{subEvent}', [CommitteeController::class, 'updateSubEvent'])->name('sub_events.update');
        Route::delete('/sub-events/{subEvent}', [CommitteeController::class, 'deleteSubEvent'])->name('sub_events.delete');

        Route::get('/events/{event}/attendance', [CommitteeController::class, 'showAttendanceScanner'])->name('events.attendance.scanner');
        Route::post('/events/{event}/scan', [CommitteeController::class, 'scanAttendance'])->name('events.attendance.scan');

        Route::get('/events/{event}/session-attendance', [CommitteeController::class, 'showSessionAttendanceScanner'])->name('events.session_attendance.scanner');
Route::post('/events/{event}/scan-session-attendance', [CommitteeController::class, 'scanSessionAttendance'])->name('events.scan-session-attendance'); // <--- PASTIKAN BARIS INI ADA DAN NAMANYA BENAR
        Route::get('/events/{event}/certificates', [CommitteeController::class, 'uploadCertificateForm'])->name('events.certificates.upload_form');
        Route::post('/events/{event}/certificates/upload', [CommitteeController::class, 'uploadCertificates'])->name('events.certificates.upload');
    });

}); // Tutup grup middleware('auth')
