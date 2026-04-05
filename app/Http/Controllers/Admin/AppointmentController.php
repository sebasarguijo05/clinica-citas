<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'doctor'])
            ->orderBy('date', 'asc')
            ->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'doctor']);
        $doctors = Doctor::where('active', true)->orderBy('name')->get();
        return view('admin.appointments.show', compact('appointment', 'doctors'));
    }

   public function approve(Appointment $appointment)
{
    $appointment->update(['status' => 'approved']);

    // Crear evento en Google Calendar si el admin tiene token
    $admin = auth()->user();

    if ($admin->google_token) {
        $google = new \App\Services\GoogleCalendarService();

        $startDateTime = $appointment->date->format('Y-m-d') . 'T' . $appointment->time;
        $endDateTime   = $appointment->date->format('Y-m-d') . 'T' .
            \Carbon\Carbon::parse($appointment->time)->addMinutes(30)->format('H:i:s');

        $eventId = $google->createEvent($admin, [
            'title'       => 'Cita: ' . $appointment->user->name . ' con ' . $appointment->doctor->name,
            'description' => 'Motivo: ' . ($appointment->reason ?? 'No especificado') .
                             '\nPaciente: ' . $appointment->user->email,
            'start'       => $startDateTime,
            'end'         => $endDateTime,
        ]);

        if ($eventId) {
            $appointment->update(['google_event_id' => $eventId]);
        }

        // También crear evento para el paciente si tiene token
        $patient = $appointment->user;
        if ($patient->google_token) {
            $google->createEvent($patient, [
                'title'       => 'Cita médica con ' . $appointment->doctor->name,
                'description' => 'Especialidad: ' . $appointment->doctor->specialty .
                                 '\nMotivo: ' . ($appointment->reason ?? 'No especificado'),
                'start'       => $startDateTime,
                'end'         => $endDateTime,
            ]);
        }
    }

    return back()->with('success', 'Cita aprobada exitosamente.');
}

    public function reject(Appointment $appointment)
    {
        $appointment->update(['status' => 'rejected']);

        return back()->with('success', 'Cita rechazada.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date'      => 'required|date|after_or_equal:today',
            'time'      => 'required',
        ]);

        $appointment->update($validated);

        return back()->with('success', 'Cita reprogramada exitosamente.');
    }

    public function saveNotes(Request $request, Appointment $appointment)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $appointment->update(['admin_notes' => $request->admin_notes]);

        return back()->with('success', 'Notas guardadas.');
    }
}