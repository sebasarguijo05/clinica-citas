<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Lista de conversaciones del paciente
    public function index()
    {
        $conversations = Appointment::where('user_id', auth()->id())
            ->with(['doctor', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->whereHas('messages')
            ->orderByDesc(function($q) {
                $q->select('created_at')
                  ->from('appointment_messages')
                  ->whereColumn('appointment_id', 'appointments.id')
                  ->latest()
                  ->limit(1);
            })
            ->get();

        $allAppointments = Appointment::where('user_id', auth()->id())
            ->with('doctor')
            ->orderBy('date', 'desc')
            ->get();

        return view('patient.messages.index', compact('conversations', 'allAppointments'));
    }

    // Chat de una cita específica
    public function show(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $appointment->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversations = Appointment::where('user_id', auth()->id())
            ->with(['doctor', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->orderBy('date', 'desc')
            ->get();

        $appointment->load(['doctor', 'messages.user']);

        return view('patient.messages.show', compact('appointment', 'conversations'));
    }

    // Enviar mensaje
    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => [
                'required',
                'string',
                'min:1',
                'max:1000',
            ],
        ], [
            'message.required' => 'El mensaje no puede estar vacío.',
            'message.max'      => 'El mensaje no puede exceder 1000 caracteres.',
        ]);

        AppointmentMessage::create([
            'appointment_id' => $appointment->id,
            'user_id'        => auth()->id(),
            'message'        => strip_tags(trim($request->message)),
        ]);

        return back();
    }
}