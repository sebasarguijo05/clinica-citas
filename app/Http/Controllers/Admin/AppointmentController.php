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
    $query = Appointment::with(['user', 'doctor'])->orderBy('date', 'asc');

    $status = request('status', 'all');

    if ($status === 'cancelled') {
        $query->where('is_cancelled', true);
    } elseif ($status !== 'all') {
        $query->where('status', $status)->where('is_cancelled', false);
    }

    $appointments = $query->paginate(15);

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

    $startDateTime = $appointment->date->format('Y-m-d') . 'T' . $appointment->time;
    $endDateTime   = $appointment->date->format('Y-m-d') . 'T' .
        \Carbon\Carbon::parse($appointment->time)->addMinutes(30)->format('H:i:s');

    $patient = $appointment->user;
    $admin   = auth()->user();

    // ✅ Evento para el PACIENTE — instancia propia del servicio
    if ($patient->google_token) {
        $googlePatient = new \App\Services\GoogleCalendarService();
        $googlePatient->createEvent($patient, [
            'title'       => 'Cita médica — ' . $appointment->doctor->name,
            'description' => 'Especialidad: ' . $appointment->doctor->specialty .
                             '\nMotivo: ' . ($appointment->reason ?? 'No especificado'),
            'start'       => $startDateTime,
            'end'         => $endDateTime,
        ]);
    }

    // ✅ Evento para el ADMIN — instancia separada del servicio
    if ($admin->google_token) {
        $googleAdmin = new \App\Services\GoogleCalendarService();
        $adminEventId = $googleAdmin->createEvent($admin, [
            'title'       => 'Cita: ' . $patient->name . ' — ' . $appointment->doctor->name,
            'description' => 'Paciente: ' . $patient->email .
                             '\nMotivo: ' . ($appointment->reason ?? 'No especificado'),
            'start'       => $startDateTime,
            'end'         => $endDateTime,
        ]);

        if ($adminEventId) {
            $appointment->update(['google_event_id' => $adminEventId]);
        }
    }

    return back()->with('success', 'Cita aprobada exitosamente.');
}

   public function reject(Appointment $appointment)
{
    // Eliminar evento de Google Calendar si existe
    if ($appointment->google_event_id && auth()->user()->google_token) {
        $google = new \App\Services\GoogleCalendarService();
        $google->deleteEvent(auth()->user(), $appointment->google_event_id);
    }

    $appointment->update([
        'status'          => 'rejected',
        'google_event_id' => null,
    ]);

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

public function cancel(Appointment $appointment)
{
    if ($appointment->google_event_id) {
        if (auth()->user()->google_token) {
            $googleAdmin = new \App\Services\GoogleCalendarService();
            $googleAdmin->deleteEvent(auth()->user(), $appointment->google_event_id);
        }
        $patient = $appointment->user;
        if ($patient->google_token) {
            $googlePatient = new \App\Services\GoogleCalendarService();
            $googlePatient->deleteEvent($patient, $appointment->google_event_id);
        }
    }

    $appointment->update([
        'is_cancelled'    => true,
        'google_event_id' => null,
    ]);

    return back()->with('success', 'Cita cancelada. El historial queda guardado.');
}


}