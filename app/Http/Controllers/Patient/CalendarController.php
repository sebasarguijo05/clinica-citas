<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('patient.calendar');
    }

    public function events()
    {
        $appointments = Appointment::where('user_id', auth()->id())
            ->with('doctor')
            ->get();

        $events = $appointments->map(function ($appointment) {
            $color = match($appointment->status) {
                'approved' => '#16a34a',
                'rejected' => '#dc2626',
                default    => '#d97706',
            };

            return [
                'id'    => $appointment->id,
                'title' => 'Dr. ' . $appointment->doctor->name,
                'start' => $appointment->date->format('Y-m-d') . 'T' . $appointment->time,
                'color' => $color,
                'url'   => route('patient.appointments.show', $appointment),
                'extendedProps' => [
                    'status'    => $appointment->statusLabel(),
                    'specialty' => $appointment->doctor->specialty,
                ],
            ];
        });

        return response()->json($events);
    }
}