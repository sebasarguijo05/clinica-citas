<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — MediCita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-8 justify-center">
            <div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="font-bold text-slate-800 text-xl">MediCita</span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">

            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800 mb-1">Recuperar contraseña</h2>
                <p class="text-slate-500 text-sm">
                    Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
                </p>
            </div>

            @if(session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

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
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition
                            {{ $errors->has('email') ? 'border-red-400' : 'border-slate-200' }}"
                            placeholder="correo@ejemplo.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 px-4 rounded-lg transition text-sm flex items-center justify-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Enviar enlace de recuperación
                </button>

                <p class="text-center text-sm text-slate-500 pt-1">
                    <a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-600 font-medium inline-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al login
                    </a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>