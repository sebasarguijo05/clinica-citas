<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — MediCita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-200 font-sans min-h-screen">

<div class="flex min-h-screen">

    {{-- ─── SIDEBAR ──────────────────────────────────────────── --}}
    <aside class="w-60 bg-slate-900 text-white flex flex-col fixed h-full z-20 shadow-xl">

        {{-- Logo --}}
        <div class="px-5 py-4 border-b border-slate-700/60">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-sm text-white leading-tight">MediCita</p>
                    <p class="text-slate-400 text-xs">Panel Admin</p>
                </div>
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Principal</p>

            @php
                $pendingCount = \App\Models\Appointment::where('status','pending')->count();
                $unreadMessages = \App\Models\AppointmentMessage::whereHas('appointment')
                    ->where('user_id', '!=', auth()->id())
                    ->where('is_read', false)->count();

                $navItems = [
                    [
                        'route' => 'admin.dashboard',
                        'match' => 'admin.dashboard',
                        'label' => 'Dashboard',
                        'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    ],
                    [
                        'route' => 'admin.appointments.index',
                        'match' => 'admin.appointments.*',
                        'label' => 'Citas',
                        'icon'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        'badge' => $pendingCount,
                    ],
                    [
                        'route' => 'admin.doctors.index',
                        'match' => 'admin.doctors.*',
                        'label' => 'Doctores',
                        'icon'  => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'route' => 'admin.calendar',
                        'match' => 'admin.calendar*',
                        'label' => 'Calendario',
                        'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    ],
                    [
                        'route' => 'admin.messages.index',
                        'match' => 'admin.messages*',
                        'label' => 'Mensajes',
                        'icon'  => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                        'badge' => $unreadMessages,
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group
               {{ request()->routeIs($item['match'])
                    ? 'bg-orange-500 text-white'
                    : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                </svg>
                <span class="flex-1">{{ $item['label'] }}</span>
                @if(!empty($item['badge']) && $item['badge'] > 0)
                    <span class="bg-orange-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center
                        {{ request()->routeIs($item['match']) ? 'bg-white text-orange-500' : '' }}">
                        {{ $item['badge'] }}
                    </span>
                @endif
            </a>
            @endforeach
        </nav>

        {{-- Usuario --}}
        <div class="px-3 py-4 border-t border-slate-700/60">
            <div class="flex items-center gap-3 px-3 py-2 mb-1">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400">Administrador</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-400 hover:bg-slate-800 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── MAIN ──────────────────────────────────────────────── --}}
    <div class="ml-60 flex-1 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="bg-slate-800 border-b border-slate-700 px-8 py-3.5 flex justify-between items-center sticky top-0 z-10">
            <div>
                <h1 class="text-white font-semibold text-base">@yield('title', 'Dashboard')</h1>
                <p class="text-slate-400 text-xs mt-0.5">{{ now()->isoFormat('dddd, D [de] MMMM YYYY') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()->google_token)
                    <span class="flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-900/40 border border-emerald-700/50 px-2.5 py-1 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        Google conectado
                    </span>
                @else
                    <a href="{{ route('google.auth') }}"
                       class="flex items-center gap-1.5 text-xs text-slate-300 bg-slate-700 hover:bg-slate-600 px-2.5 py-1 rounded-full transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Conectar Google
                    </a>
                @endif
            </div>
        </header>

        {{-- Alertas --}}
        @if(session('success') || session('error'))
        <div class="px-8 pt-5">
            @if(session('success'))
                <div class="bg-emerald-900/30 border border-emerald-700/50 text-emerald-300 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-900/30 border border-red-700/50 text-red-300 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
        @endif

        {{-- Contenido --}}
        <main class="px-8 py-6 flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-8 py-3 border-t border-slate-300 bg-slate-200">
            <p class="text-xs text-slate-400">© {{ date('Y') }} MediCita — Sistema de Gestión de Citas Médicas</p>
        </footer>
    </div>
</div>

</body>
</html>