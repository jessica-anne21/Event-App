<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // Load subEvents dan hitung sessionRegistrations untuk setiap subEvent
        $events = Event::with(['subEvents' => function($query) {
            $query->withCount('sessionRegistrations')->orderBy('date')->orderBy('start_time');
        }])->orderBy('date', 'asc')->get();

        return view('events.index', compact('events'));
    }

    // --- MODIFIKASI METODE show() ---
    public function show(Event $event)
    {
        // Load subEvents dan hitung sessionRegistrations untuk setiap subEvent di halaman detail
        $event->load(['subEvents' => function($query) {
            $query->withCount('sessionRegistrations')->orderBy('date')->orderBy('start_time');
        }]);

        return view('events.show', compact('event'));
    }
    // --- AKHIR MODIFIKASI METODE show() ---
}