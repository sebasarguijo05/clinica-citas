<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña — MediCita</title>
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
                <h2 class="text-xl font-bold text-slate-800 mb-1">Nueva contraseña</h2>
                <p class="text-slate-500 text-sm">Elige una contraseña segura para tu cuenta.</p>
            </div>

            {{-- Indicador de requisitos --}}
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-6">
                <p class="text-xs font-semibold text-slate-600 mb-2">La contraseña debe tener:</p>
                <ul class="space-y-1 text-xs text-slate-500">
                    <li class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        </svg>
                        Mínimo 8 caracteres
                    </li>
                    <li class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        </svg>
                        Letras mayúsculas y minúsculas
                    </li>
                    <li class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        </svg>
                        Al menos un número
                    </li>
                    <li class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                        </svg>
                        Al menos un símbolo (!@#$%...)
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required
                        class="w-full px-3 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent
                        {{ $errors->has('email') ? 'border-red-400' : 'border-slate-200' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nueva contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-3 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent
                            {{ $errors->has('password') ? 'border-red-400' : 'border-slate-200' }}"
                            placeholder="Mínimo 8 caracteres">
                        <button type="button" onclick="togglePassword('password', 'eye1')"
                            class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmar contraseña</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-3 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent border-slate-200"
                            placeholder="Repite tu contraseña">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                            class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="eye2" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 px-4 rounded-lg transition text-sm shadow-sm">
                    Restablecer contraseña
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, eyeId) {
            const field = document.getElementById(fieldId);
            field.type = field.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>