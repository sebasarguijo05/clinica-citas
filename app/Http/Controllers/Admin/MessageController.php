<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Lista de todas las conversaciones
    public function index()
    {
        $conversations = Appointment::with(['user', 'doctor', 'messages' => function($q) {
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

        return view('admin.messages.index', compact('conversations'));
    }

    // Chat de una cita específica
    public function show(Appointment $appointment)
    {
        // Marcar mensajes del paciente como leídos
        $appointment->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversations = Appointment::with(['user', 'doctor', 'messages' => function($q) {
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

        $appointment->load(['user', 'doctor', 'messages.user']);

        return view('admin.messages.show', compact('appointment', 'conversations'));
    }

    // Enviar mensaje
   public function store(Request $request, Appointment $appointment)
{
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