<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar');
    }

    public function events()
    {
        $appointments = Appointment::with(['user', 'doctor'])->get();

        $events = $appointments->map(function ($appointment) {
            $color = match($appointment->status) {
                'approved' => '#16a34a',
                'rejected' => '#dc2626',
                default    => '#d97706',
            };

            return [
                'id'    => $appointment->id,
                'title' => $appointment->user->name . ' — Dr. ' . $appointment->doctor->name,
                'start' => $appointment->date->format('Y-m-d') . 'T' . $appointment->time,
                'color' => $color,
                'url'   => route('admin.appointments.show', $appointment),
                'extendedProps' => [
                    'status'    => $appointment->statusLabel(),
                    'patient'   => $appointment->user->name,
                    'doctor'    => $appointment->doctor->name,
                ],
            ];
        });

        return response()->json($events);
    }
}