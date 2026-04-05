<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Mis Citas</h2>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-200">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Mis Citas</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Historial y estado de tus citas médicas</p>
                </div>
                <a href="{{ route('patient.appointments.create') }}"
                   class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nueva Cita
                </a>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-4 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Hora</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($appointments as $appointment)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-slate-800 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                        {{ strtoupper(substr($appointment->doctor->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $appointment->doctor->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $appointment->doctor->specialty }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $appointment->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                            <td class="px-6 py-4">
                                @php $color = $appointment->statusColor(); @endphp
                                @if($color === 'green')
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        {{ $appointment->statusLabel() }}
                                    </span>
                                @elseif($color === 'yellow')
                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                                        {{ $appointment->statusLabel() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        {{ $appointment->statusLabel() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('patient.appointments.show', $appointment) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-1.5 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>

                                    @if($appointment->isPending())
                                    <form method="POST" action="{{ route('patient.appointments.destroy', $appointment) }}"
                                          onsubmit="return confirm('¿Cancelar esta cita?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancelar
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-slate-400 text-sm">No tienes citas registradas.</p>
                                    <a href="{{ route('patient.appointments.create') }}" class="text-orange-500 text-sm hover:underline">Solicita tu primera cita</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $appointments->links() }}</div>
        </div>
    </div>
</x-app-layout>