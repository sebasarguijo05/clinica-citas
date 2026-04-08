@extends('layouts.admin')
@section('title', 'Gestión de Citas')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-lg font-semibold text-slate-800">Gestión de Citas</h2>
        <p class="text-sm text-slate-500 mt-0.5">Revisa, aprueba o rechaza las solicitudes de citas</p>
    </div>
</div>

{{-- Filtros --}}
<div class="flex flex-wrap gap-2 mb-5">
    @foreach(['all' => 'Todas', 'pending' => 'Pendientes', 'approved' => 'Aprobadas', 'rejected' => 'Rechazadas', 'cancelled' => 'Canceladas'] as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium border transition
           {{ request('status', 'all') === $val
               ? 'bg-slate-800 text-white border-slate-800'
               : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Paciente</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Doctor</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Hora</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($appointments as $appointment)
            <tr class="hover:bg-slate-50 transition {{ $appointment->isCancelled() ? 'opacity-60' : '' }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-slate-700 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($appointment->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $appointment->user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $appointment->user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $appointment->doctor->name }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $appointment->date->format('d/m/Y') }}</td>
                <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</td>
                <td class="px-6 py-4">
                    @if($appointment->status === 'approved')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            Aprobada
                        </span>
                    @elseif($appointment->status === 'pending')
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                            Pendiente
                        </span>
                    @elseif($appointment->status === 'cancelled')
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                            Cancelada
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                            Rechazada
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.appointments.show', $appointment) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-1.5 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver detalle
                            @php $unread = $appointment->unreadMessagesCount(auth()->id()); @endphp
                            @if($unread > 0)
                                <span class="bg-orange-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                                    {{ $unread }}
                                </span>
                            @endif
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-slate-400 text-sm">No hay citas registradas.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $appointments->links() }}</div>
@endsection