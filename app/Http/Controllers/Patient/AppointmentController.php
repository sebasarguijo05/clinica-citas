<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('user_id', auth()->id())
            ->with('doctor')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = Doctor::where('active', true)->orderBy('name')->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'doctor_id' => [
            'required',
            'integer',
            'exists:doctors,id',
        ],
        'date' => [
            'required',
            'date_format:Y-m-d',
            'after_or_equal:today',
            'before:' . now()->addMonths(3)->format('Y-m-d'), // Máx 3 meses adelante
        ],
        'time' => [
            'required',
            'in:08:00,08:30,09:00,09:30,10:00,10:30,11:00,11:30,13:00,13:30,14:00,14:30,15:00,15:30,16:00',
        ],
        'reason' => [
            'nullable',
            'string',
            'min:5',
            'max:500',
        ],
    ], [
        'doctor_id.required'    => 'Debes seleccionar un doctor.',
        'doctor_id.exists'      => 'El doctor seleccionado no es válido.',
        'date.required'         => 'La fecha es obligatoria.',
        'date.after_or_equal'   => 'La fecha no puede ser en el pasado.',
        'date.before'           => 'La fecha no puede ser mayor a 3 meses desde hoy.',
        'time.required'         => 'La hora es obligatoria.',
        'time.in'               => 'La hora seleccionada no es válida.',
        'reason.min'            => 'El motivo debe tener al menos 5 caracteres.',
        'reason.max'            => 'El motivo no puede exceder 500 caracteres.',
    ]);

    $validated['user_id'] = auth()->id();
    $validated['status']  = 'pending';

    // Verificar disponibilidad del doctor
    $exists = Appointment::where('doctor_id', $validated['doctor_id'])
        ->where('date', $validated['date'])
        ->where('time', $validated['time'])
        ->whereIn('status', ['pending', 'approved'])
        ->exists();

    if ($exists) {
        return back()->withErrors([
            'time' => 'Ese horario ya está ocupado. Por favor elige otro.',
        ])->withInput();
    }

    Appointment::create($validated);

    return redirect()->route('patient.appointments.index')
        ->with('success', 'Cita solicitada exitosamente. Espera la confirmación.');
}

    public function show(Appointment $appointment)
    {
        // Verificar que la cita pertenece al paciente autenticado
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $appointment->load('doctor');
        return view('patient.appointments.show', compact('appointment'));
    }

public function destroy(Appointment $appointment)
{
    if ($appointment->user_id !== auth()->id()) {
        abort(403);
    }

    if ($appointment->isRejected()) {
        return back()->with('error', 'Esta cita ya no puede ser cancelada.');
    }

    // Borrar del calendario del ADMIN
    if ($appointment->google_event_id) {
        $admin = \App\Models\User::where('role', 'admin')
            ->whereNotNull('google_token')->first();
        if ($admin) {
            $googleAdmin = new \App\Services\GoogleCalendarService();
            $googleAdmin->deleteEvent($admin, $appointment->google_event_id);
        }

        // Borrar del calendario del PACIENTE
        if (auth()->user()->google_token) {
            $googlePatient = new \App\Services\GoogleCalendarService();
            $googlePatient->deleteEvent(auth()->user(), $appointment->google_event_id);
        }
    }

    // Cambiar estado a rechazada en la plataforma
    $appointment->update([
        'status'          => 'rejected',
        'google_event_id' => null,
    ]);

    return redirect()->route('patient.appointments.index')
        ->with('success', 'Cita cancelada exitosamente.');
}

 
}