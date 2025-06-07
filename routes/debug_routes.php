<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommitteeController;

Route::post('/committee/events/{event}/scan-debug', [CommitteeController::class, 'scanAttendance'])->name('debug.scan');

require __DIR__.'/debug_routes.php';