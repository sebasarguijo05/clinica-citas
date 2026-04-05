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
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relación: la cita pertenece a un paciente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: la cita pertenece a un doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Helpers de estado
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

    // Etiqueta visual del estado
    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'  => 'Pendiente',
            'approved' => 'Aprobada',
            'rejected' => 'Rechazada',
            default    => 'Desconocido',
        };
    }

    // Color del badge según estado
    public function statusColor(): string
    {
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