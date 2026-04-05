<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'message',
        'is_read',
    ];

    // Mensaje pertenece a una cita
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Mensaje pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}