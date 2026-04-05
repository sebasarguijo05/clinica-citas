<nav x-data="{ open: false, dropdown: false }" class="bg-slate-900 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14 items-center">

            {{-- Logo + Links --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-orange-500 rounded-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="font-bold text-sm text-white">MediCita</span>
                </a>

                <div class="hidden sm:flex items-center gap-1">
                   @php
    $links = [
        ['route' => 'dashboard',                  'match' => 'dashboard',              'label' => 'Panel'],
        ['route' => 'patient.appointments.index', 'match' => 'patient.appointments.*', 'label' => 'Mis Citas'],
        ['route' => 'patient.calendar',           'match' => 'patient.calendar*',      'label' => 'Calendario'],
    ];
    $unread = \App\Models\AppointmentMessage::whereHas('appointment', function($q) {
        $q->where('user_id', auth()->id());
    })->where('user_id', '!=', auth()->id())
      ->where('is_read', false)->count();
@endphp

@foreach($links as $link)
<a href="{{ route($link['route']) }}"
   class="px-3 py-1.5 rounded-md text-sm font-medium transition
   {{ request()->routeIs($link['match'])
        ? 'bg-slate-700 text-white'
        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    {{ $link['label'] }}
</a>
@endforeach

{{-- Mensajes con badge --}}
<a href="{{ route('patient.messages.index') }}"
   class="relative px-3 py-1.5 rounded-md text-sm font-medium transition
   {{ request()->routeIs('patient.messages*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    Mensajes
    @if($unread > 0)
        <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
            {{ $unread }}
        </span>
    @endif
</a>
                </div>
            </div>

            {{-- Derecha --}}
            <div class="hidden sm:flex items-center gap-3">
                <div class="relative">
                    <button @click="dropdown = !dropdown"
                        class="flex items-center gap-2 text-sm text-slate-300 hover:text-white px-2 py-1.5 rounded-lg hover:bg-slate-800 transition">
                        <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm">{{ auth()->user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="dropdown" @click.outside="dropdown = false"
                         class="absolute right-0 mt-1.5 w-52 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50 text-slate-700">
                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <p class="text-xs font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-slate-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mi Perfil
                        </a>
                        <a href="{{ route('google.auth') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-slate-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ auth()->user()->google_token ? 'Google conectado' : 'Conectar Google' }}
                        </a>
                        <div class="border-t border-slate-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile toggle --}}
            <button @click="open = !open" class="sm:hidden text-slate-400 hover:text-white p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Menú móvil --}}
    <div x-show="open" class="sm:hidden border-t border-slate-700 bg-slate-800 px-4 py-3 space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded text-sm text-slate-300 hover:bg-slate-700">Panel</a>
        <a href="{{ route('patient.appointments.index') }}" class="block px-3 py-2 rounded text-sm text-slate-300 hover:bg-slate-700">Mis Citas</a>
        <a href="{{ route('patient.calendar') }}" class="block px-3 py-2 rounded text-sm text-slate-300 hover:bg-slate-700">Calendario</a>
        <a href="{{ route('patient.appointments.index') }}" class="block px-3 py-2 rounded text-sm text-slate-300 hover:bg-slate-700">Mensajes</a>
        <div class="border-t border-slate-700 pt-2 mt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded text-sm text-red-400 hover:bg-slate-700">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</nav>