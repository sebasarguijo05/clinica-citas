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
        return false;
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'  => 'Pendiente',
            'approved' => 'Aprobada',
            'rejected' => 'Rechazada',
            default    => 'Desconocido',
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending'  => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default    => 'gray',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function messages()
    {
        return $this->hasMany(AppointmentMessage::class)->orderBy('created_at', 'asc');
    }

    public function unreadMessagesCount(int $userId): int
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}