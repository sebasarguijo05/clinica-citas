<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Bienvenido, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-200">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @php
                $pending  = auth()->user()->appointments()->where('status', 'pending')->count();
                $approved = auth()->user()->appointments()->where('status', 'approved')->count();
                $total    = auth()->user()->appointments()->count();
                $next     = auth()->user()->appointments()
                                ->where('status', 'approved')
                                ->where('date', '>=', today())
                                ->with('doctor')
                                ->orderBy('date')->orderBy('time')
                                ->first();
                $unread = \App\Models\AppointmentMessage::whereHas('appointment', function($q) {
                    $q->where('user_id', auth()->id());
                })->where('user_id', '!=', auth()->id())
                  ->where('is_read', false)->count();
            @endphp

            {{-- Próxima cita --}}
            @if($next)
            <div class="bg-slate-800 rounded-xl p-6 mb-6 shadow-lg relative overflow-hidden">
                {{-- Decoración de fondo --}}
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-orange-500 rounded-full opacity-10"></div>
                <div class="absolute -bottom-10 -left-10 w-36 h-36 bg-slate-600 rounded-full opacity-20"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Tu próxima cita</p>
                    </div>

                    <h2 class="text-xl font-bold text-white">{{ $next->doctor->name }}</h2>
                    <p class="text-slate-400 text-sm mt-0.5">{{ $next->doctor->specialty }}</p>

                    <div class="flex gap-5 mt-4">
                        <div class="flex items-center gap-2 text-sm text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $next->date->isoFormat('dddd D [de] MMMM') }}
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($next->time)->format('h:i A') }}
                        </div>
                    </div>

                    <a href="{{ route('patient.appointments.show', $next) }}"
                       class="inline-flex items-center gap-2 mt-5 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Ver detalle
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endif

            {{-- Estadísticas --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-400">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Pendientes</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $pending }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Aprobadas</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $approved }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-slate-500">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $total }}</p>
                </div>
            </div>

            {{-- Acciones + Google Calendar --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Acciones Rápidas --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Acciones Rápidas</h3>
                    <div class="space-y-2">

                        <a href="{{ route('patient.appointments.create') }}"
                           class="flex items-center gap-3 bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg transition font-medium text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Solicitar Nueva Cita
                        </a>

                        <a href="{{ route('patient.appointments.index') }}"
                           class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 text-slate-700 px-4 py-3 rounded-lg transition border border-slate-200 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Ver Todas Mis Citas
                        </a>

                        <a href="{{ route('patient.messages.index') }}"
                           class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 text-slate-700 px-4 py-3 rounded-lg transition border border-slate-200 text-sm relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Mensajes
                            @if($unread > 0)
                                <span class="ml-auto bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ $unread }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('patient.calendar') }}"
                           class="flex items-center gap-3 bg-slate-50 hover:bg-slate-100 text-slate-700 px-4 py-3 rounded-lg transition border border-slate-200 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Ver Calendario
                        </a>
                    </div>
                </div>

                {{-- Google Calendar --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-4">Google Calendar</h3>
                    @if(auth()->user()->google_token)
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                            <span class="text-sm font-medium text-emerald-600">Conectado</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Recibirás un evento en tu calendario cuando una cita sea aprobada.
                        </p>
                    @else
                        <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                            Conecta tu cuenta para recibir tus citas directamente en Google Calendar.
                        </p>
                        <a href="{{ route('google.auth') }}"
                           class="inline-flex items-center gap-2 border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2.5 rounded-lg text-sm transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Conectar con Google
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>