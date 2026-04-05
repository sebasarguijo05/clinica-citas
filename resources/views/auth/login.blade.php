<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Clínica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/1.0.6/heroicons.min.css">
</head>
<body class="min-h-screen bg-slate-900 flex">

    {{-- Panel izquierdo decorativo --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden flex-col justify-between p-12">

        {{-- Grid decorativo de fondo --}}
        <div class="absolute inset-0 opacity-5"
             style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 40px 40px;">
        </div>

        {{-- Círculo decorativo --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-orange-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>

        {{-- Logo y nombre --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-white font-bold text-xl tracking-tight">MediCita</span>
            </div>

            <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                Gestión de citas<br>
                <span class="text-orange-400">médicas simplificada.</span>
            </h1>
            <p class="text-slate-400 text-lg leading-relaxed">
                Administra tu clínica, pacientes y doctores desde un solo lugar.
            </p>
        </div>

        {{-- Features --}}
        <div class="relative z-10 space-y-4">
            @foreach([
                ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'text' => 'Agenda y calendario integrado'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'text' => 'Gestión de doctores y pacientes'],
                ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'text' => 'Mensajes directos por cita'],
                ['icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 5H4m0 0l4 4m-4-4l4-4', 'text' => 'Sincronización con Google Calendar'],
            ] as $feature)
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/>
                    </svg>
                </div>
                <span class="text-slate-300 text-sm">{{ $feature['text'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Panel derecho — formulario --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-slate-50 px-8 py-12">
        <div class="w-full max-w-md">

            {{-- Header del form --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6 lg:hidden">
                    <div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800 text-lg">MediCita</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Bienvenido de nuevo</h2>
                <p class="text-slate-500 mt-1 text-sm">Ingresa tus credenciales para continuar</p>
            </div>

            {{-- Errores de sesión --}}
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-white border rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition
                            {{ $errors->has('email') ? 'border-red-400' : 'border-slate-200' }}"
                            placeholder="correo@ejemplo.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-sm font-medium text-slate-700">Contraseña</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-orange-500 hover:text-orange-600 font-medium">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 bg-white border rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition
                            {{ $errors->has('password') ? 'border-red-400' : 'border-slate-200' }}"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recuérdame --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                    <label for="remember" class="text-sm text-slate-600">Mantener sesión iniciada</label>
                </div>

                {{-- Botón --}}
                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 px-4 rounded-lg transition text-sm flex items-center justify-center gap-2 shadow-sm">
                    Iniciar Sesión
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>

                {{-- Registro --}}
                @if(Route::has('register'))
                <p class="text-center text-sm text-slate-500 pt-2">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-orange-500 hover:text-orange-600 font-medium">
                        Crear cuenta
                    </a>
                </p>
                @endif
            </form>
        </div>
    </div>

</body>
</html>