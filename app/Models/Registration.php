<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'registration_code',
        'payment_status',
        'payment_proof_path',
        'attended',
        'certificate_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // --- Tambahkan relasi ini ---
    public function sessionRegistrations()
    {
        return $this->hasMany(SessionRegistration::class);
    }
}