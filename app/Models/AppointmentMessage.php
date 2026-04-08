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


public function isPending(): bool
{
    return $this->status === 'pending';
}

public function isApproved(): bool
{
    return $this->status === 'approved';
}

public function isRejected(): bool
{
    return $this->status === 'rejected';
}

public function isCancelled(): bool
{
    return $this->status === 'cancelled';
}

public function statusLabel(): string
{
    return match($this->status) {
        'pending'   => 'Pendiente',
        'approved'  => 'Aprobada',
        'rejected'  => 'Rechazada',
        'cancelled' => 'Cancelada',
        default     => 'Desconocido',
    };
}

public function statusColor(): string
{
    return match($this->status) {
        'pending'   => 'yellow',
        'approved'  => 'green',
        'rejected'  => 'red',
        'cancelled' => 'gray',
        default     => 'gray',
    };
}


}