@extends('layouts.admin')
@section('title', 'Detalle de Cita')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.appointments.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-sm text-slate-500">Detalle de Cita</span>
    </div>

    {{-- Card principal --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-5">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($appointment->user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-800">{{ $appointment->user->name }}</p>
                    <p class="text-xs text-slate-400">{{ $appointment->user->email }}</p>
                </div>
            </div>
            @if($appointment->status === 'approved')
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium px-3 py-1.5 rounded-full border border-emerald-200">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aprobada
                </span>
            @elseif($appointment->status === 'pending')
                <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-medium px-3 py-1.5 rounded-full border border-amber-200">
                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span> Pendiente
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-medium px-3 py-1.5 rounded-full border border-red-200">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Rechazada
                </span>
            @endif
        </div>

        {{-- Info --}}
        <div class="px-6 py-5">
            <div class="grid grid-cols-2 gap-4 text-sm mb-5">
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Doctor</p>
                    <p class="font-semibold text-slate-800">{{ $appointment->doctor->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Especialidad</p>
                    <p class="font-semibold text-slate-800">{{ $appointment->doctor->specialty }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Fecha</p>
                    <p class="font-semibold text-slate-800">{{ $appointment->date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Hora</p>
                    <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</p>
                </div>
            </div>

            @if($appointment->reason)
            <div class="bg-slate-50 rounded-lg px-4 py-3 mb-5 border border-slate-200">
                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Motivo del paciente</p>
                <p class="text-slate-700 text-sm">{{ $appointment->reason }}</p>
            </div>
            @endif

         {{-- Botones de acción --}}
<div class="flex flex-wrap gap-3 mb-5">
    @if($appointment->isPending())
    <form method="POST" action="{{ route('admin.appointments.approve', $appointment) }}">
        @csrf @method('PATCH')
        <button type="submit"
            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Aprobar Cita
        </button>
    </form>
    <form method="POST" action="{{ route('admin.appointments.reject', $appointment) }}">
        @csrf @method('PATCH')
        <button type="submit"
            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Rechazar Cita
        </button>
    </form>
    @endif

    @if(!$appointment->isRejected())
    <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}"
          onsubmit="return confirm('¿Cancelar esta cita? Se eliminará el evento de Google Calendar.')">
        @csrf @method('PATCH')
        <button type="submit"
            class="inline-flex items-center gap-2 bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            Cancelar Cita
        </button>
    </form>
    @endif
</div>
            {{-- Reprogramar --}}
            <div class="border-t border-slate-100 pt-5 mb-5">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-3">Reprogramar Cita</p>
                <form method="POST" action="{{ route('admin.appointments.reschedule', $appointment) }}" class="grid grid-cols-3 gap-3">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Doctor</label>
                        <select name="doctor_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Fecha</label>
                        <input type="date" name="date" value="{{ $appointment->date->format('Y-m-d') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Hora</label>
                        <select name="time" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-slate-700 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00'] as $hora)
                                <option value="{{ $hora }}" {{ $appointment->time == $hora.':00' ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($hora)->format('h:i A') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Guardar Reprogramación
                        </button>
                    </div>
                </form>
            </div>

            {{-- Notas --}}
            <div class="border-t border-slate-100 pt-5">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-3">Nota para el paciente</p>
                <form method="POST" action="{{ route('admin.appointments.notes', $appointment) }}">
                    @csrf @method('PATCH')
                    <textarea name="admin_notes" rows="3"
                        placeholder="Escribe una nota visible para el paciente..."
                        class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none">{{ $appointment->admin_notes }}</textarea>
                    <button type="submit"
                        class="mt-2 inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Guardar Nota
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection