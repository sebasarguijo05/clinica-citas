<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id',
    'doctor_id',
    'date',
    'time',
    'status',
    'reason',
    'admin_notes',
    'google_event_id',
    'is_cancelled',
];

protected $casts = [
    'date'         => 'date',
    'is_cancelled' => 'boolean',
];

public function isPending(): bool
{
    return $this->status === 'pending' && !$this->is_cancelled;
}

public function isApproved(): bool
{
    return $this->status === 'approved' && !$this->is_cancelled;
}

public function isRejected(): bool
{
    return $this->status === 'rejected' && !$this->is_cancelled;
}

public function isCancelled(): bool
{
    return $this->is_cancelled === true;
}

public function statusLabel(): string
{
    if ($this->is_cancelled) return 'Cancelada';

    return match($this->status) {
        'pending'  => 'Pendiente',
        'approved' => 'Aprobada',
        'rejected' => 'Rechazada',
        default    => 'Desconocido',
    };
}

public function statusColor(): string
{
    if ($this->is_cancelled) return 'gray';

    return match($this->status) {
        'pending'  => 'yellow',
        'approved' => 'green',
        'rejected' => 'red',
        default    => 'gray',
    };
}

    // Relación: una cita tiene muchos mensajes
public function messages()
{
    return $this->hasMany(AppointmentMessage::class)->orderBy('created_at', 'asc');
}

// Contar mensajes no leídos
public function unreadMessagesCount(int $userId): int
{
    return $this->messages()
        ->where('user_id', '!=', $userId)
        ->where('is_read', false)
        ->count();
}




}