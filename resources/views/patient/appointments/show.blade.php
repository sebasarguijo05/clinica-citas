<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle de Cita
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-200">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @php $color = $appointment->statusColor(); @endphp

                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-lg font-bold text-gray-800">
                        Cita con {{ $appointment->doctor->name }}
                    </h3>
                    <span class="bg-{{ $color }}-100 text-{{ $color }}-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $appointment->statusLabel() }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                    <div>
                        <p class="text-gray-500">Especialidad</p>
                        <p class="font-medium">{{ $appointment->doctor->specialty }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fecha</p>
                        <p class="font-medium">{{ $appointment->date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Hora</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Solicitada el</p>
                        <p class="font-medium">{{ $appointment->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                @if($appointment->reason)
                <div class="mb-4">
                    <p class="text-gray-500 text-sm">Motivo</p>
                    <p class="text-gray-800 mt-1">{{ $appointment->reason }}</p>
                </div>
                @endif

                @if($appointment->admin_notes)
                <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-4">
                    <p class="text-blue-700 text-sm font-medium">Nota del administrador:</p>
                    <p class="text-blue-800 mt-1 text-sm">{{ $appointment->admin_notes }}</p>
                </div>
                @endif

                <div class="flex gap-3 mt-6">
                    <a href="{{ route('patient.appointments.index') }}"
                       class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                        ← Volver
                    </a>

                    @if($appointment->isPending())
                    <form method="POST" action="{{ route('patient.appointments.destroy', $appointment) }}"
                          onsubmit="return confirm('¿Cancelar esta cita?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                            Cancelar Cita
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>