<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
{
    // Pastikan Anda memuat relasi subEvents
    $events = Event::with('subEvents')->orderBy('date', 'asc')->get(); // Atau paginate() jika Anda menggunakan pagination
    return view('events.index', compact('events'));
}

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    // Metode lain akan ditambahkan untuk role lain
}