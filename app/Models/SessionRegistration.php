<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',    // <--- TAMBAHKAN INI
        'sub_event_id',       // <--- TAMBAHKAN INI
        'attended_session',   // <--- TAMBAHKAN INI (jika diatur saat create/update)
    ];

    // Relasi ke Pendaftaran Event Utama
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    // Relasi ke Sub-Event (Sesi)
    public function subEvent()
    {
        return $this->belongsTo(SubEvent::class);
    }
}