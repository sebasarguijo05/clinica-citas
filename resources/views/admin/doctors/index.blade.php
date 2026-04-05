@extends('layouts.admin')
@section('title', 'Doctores')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-lg font-semibold text-slate-800">Doctores</h2>
        <p class="text-sm text-slate-500 mt-0.5">Gestiona el equipo médico de la clínica</p>
    </div>
    <a href="{{ route('admin.doctors.create') }}"
       class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Doctor
    </a>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-200">
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Doctor</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Especialidad</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contacto</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($doctors as $doctor)
            <tr class="hover:bg-slate-50 transition {{ $doctor->trashed() ? 'opacity-60' : '' }}">

                {{-- Nombre --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-slate-800 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($doctor->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $doctor->name }}</p>
                            <p class="text-xs text-slate-400">{{ $doctor->email }}</p>
                        </div>
                    </div>
                </td>

                {{-- Especialidad --}}
                <td class="px-6 py-4 text-slate-600">{{ $doctor->specialty }}</td>

                {{-- Contacto --}}
                <td class="px-6 py-4 text-slate-500">{{ $doctor->phone ?? '—' }}</td>

                {{-- Estado --}}
                <td class="px-6 py-4">
                    @if($doctor->trashed())
                        <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                            Deshabilitado
                        </span>
                    @elseif($doctor->active)
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                            Inactivo
                        </span>
                    @endif
                </td>

                {{-- Acciones --}}
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        @if($doctor->trashed())
                            {{-- Restaurar --}}
                            <form method="POST" action="{{ route('admin.doctors.restore', $doctor->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Restaurar
                                </button>
                            </form>
                        @else
                            {{-- Editar --}}
                            <a href="{{ route('admin.doctors.edit', $doctor) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-3 py-1.5 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>

                            {{-- Deshabilitar --}}
                            <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}"
                                  onsubmit="return confirm('¿Deshabilitar a {{ $doctor->name }}? Podrás restaurarlo después.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Deshabilitar
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-slate-400 text-sm">No hay doctores registrados.</p>
                        <a href="{{ route('admin.doctors.create') }}" class="text-orange-500 text-sm hover:underline">Agregar el primero</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Paginación --}}
<div class="mt-4">{{ $doctors->links() }}</div>

@endsection