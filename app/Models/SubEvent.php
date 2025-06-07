<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini

class SubEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'speaker',
        'max_participants',
    ];

    // Relasi ke Event utama
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relasi ke pendaftaran sesi
    public function sessionRegistrations(): HasMany // Tambahkan atau pastikan ini ada
    {
        return $this->hasMany(SessionRegistration::class);
    }

    // --- Optional: Accessor untuk menghitung peserta saat ini ---
    // public function getCurrentParticipantsAttribute()
    // {
    //     return $this->sessionRegistrations()->count();
    // }
}