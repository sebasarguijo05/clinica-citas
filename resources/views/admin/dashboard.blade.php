@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

@php
    $totalDoctors      = \App\Models\Doctor::count();
    $totalPatients     = \App\Models\User::where('role', 'patient')->count();
    $pendingCount      = \App\Models\Appointment::where('status', 'pending')->count();
    $approvedCount     = \App\Models\Appointment::where('status', 'approved')->count();
    $todayCount        = \App\Models\Appointment::whereDate('date', today())->count();
    $recentAppointments = \App\Models\Appointment::with(['user','doctor'])
                            ->orderBy('created_at','desc')->take(5)->get();
@endphp

{{-- Tarjetas de estadísticas --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium">Doctores</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalDoctors }}</p>
            </div>
            <span class="text-3xl"></span>
        </div>
        <a href="{{ route('admin.doctors.index') }}" class="text-xs text-blue-600 hover:underline mt-3 block">
            Ver todos →
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pacientes</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPatients }}</p>
            </div>
            <span class="text-3xl"></span>
        </div>
        <p class="text-xs text-gray-400 mt-3">Registrados en el sistema</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pendientes</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $pendingCount }}</p>
            </div>
            <span class="text-3xl"></span>
        </div>
        <a href="{{ route('admin.appointments.index', ['status' => 'pending']) }}"
           class="text-xs text-yellow-600 hover:underline mt-3 block">
            Revisar →
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-gray-500 font-medium">Citas hoy</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $todayCount }}</p>
            </div>
            <span class="text-3xl"></span>
        </div>
        <a href="{{ route('admin.calendar') }}" class="text-xs text-purple-600 hover:underline mt-3 block">
            Ver calendario →
        </a>
    </div>
</div>

{{-- Citas recientes + Google Calendar --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Citas recientes --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">📋 Citas Recientes</h2>
            <a href="{{ route('admin.appointments.index') }}" class="text-xs text-blue-600 hover:underline">
                Ver todas →
            </a>
        </div>
        <div class="divide-y">
            @forelse($recentAppointments as $apt)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-sm font-bold text-blue-700">
                        {{ strtoupper(substr($apt->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $apt->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $apt->doctor->name }} · {{ $apt->date->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @php $color = $apt->statusColor(); @endphp
                    <span class="bg-{{ $color }}-100 text-{{ $color }}-700 px-2 py-1 rounded-full text-xs font-medium">
                        {{ $apt->statusLabel() }}
                    </span>
                    <a href="{{ route('admin.appointments.show', $apt) }}"
                       class="text-xs text-blue-600 hover:underline">Ver</a>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-400 text-sm">
                No hay citas registradas aún.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Panel lateral --}}
    <div class="space-y-6">
       {{-- Google Calendar --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-semibold text-gray-800 mb-3">Google Calendar</h3>
    @if(auth()->user()->google_token)
        <div class="flex items-center gap-2 text-emerald-600 text-sm mb-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
            Conectado
        </div>
        <p class="text-xs text-slate-400 mb-4">Los eventos se crean automáticamente al aprobar citas.</p>
        <div class="flex gap-2">
            <a href="{{ route('google.auth') }}"
               class="inline-flex items-center gap-2 border border-slate-200 text-slate-600 hover:bg-slate-50 px-3 py-2 rounded-lg text-xs transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reconectar
            </a>
            <form method="POST" action="{{ route('google.disconnect') }}"
                  onsubmit="return confirm('¿Desconectar Google Calendar?')">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 border border-red-200 text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg text-xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Desconectar
                </button>
            </form>
        </div>
    @else
        <p class="text-slate-500 text-sm mb-3">Conecta para crear eventos automáticamente al aprobar citas.</p>
        <a href="{{ route('google.auth') }}"
           class="inline-flex items-center gap-2 border border-slate-200 text-slate-700 hover:bg-slate-50 px-3 py-2 rounded-lg text-sm transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Conectar Google
        </a>
    @endif
</div>

       {{-- Accesos rápidos --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Accesos Rápidos</h3>
    <div class="space-y-2">
        <a href="{{ route('admin.doctors.create') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50 border border-transparent hover:border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Doctor
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'pending']) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50 border border-transparent hover:border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Citas Pendientes
            @if($pendingCount > 0)
                <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.calendar') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50 border border-transparent hover:border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Ver Calendario
        </a>
        <a href="{{ route('admin.messages.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-600 hover:bg-slate-50 border border-transparent hover:border-slate-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Mensajes
        </a>
    </div>
</div>
    </div>
</div>
@endsection