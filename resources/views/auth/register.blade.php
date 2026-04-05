<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta — Clínica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-8 justify-center">
            <div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span class="font-bold text-slate-800 text-xl">MediCita</span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-xl font-bold text-slate-800 mb-1">Crear cuenta</h2>
            <p class="text-slate-500 text-sm mb-6">Completa el formulario para registrarte como paciente</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition
                        {{ $errors->has('name') ? 'border-red-400' : 'border-slate-200' }}"
                        placeholder="Tu nombre completo">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-3 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition
                        {{ $errors->has('email') ? 'border-red-400' : 'border-slate-200' }}"
                        placeholder="correo@ejemplo.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition
                        {{ $errors->has('password') ? 'border-red-400' : 'border_slate-200' }}"
                        placeholder="Mínimo 8 caracteres">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-3 py-2.5 bg-slate-50 border rounded-lg text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                        placeholder="Repite tu contraseña">
                </div>

                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 px-4 rounded-lg transition text-sm mt-2 shadow-sm">
                    Crear cuenta
                </button>

                <p class="text-center text-sm text-slate-500 pt-1">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-orange-500 hover:text-orange-600 font-medium">
                        Iniciar sesión
                    </a>
                </p>
            </form>
        </div>
    </div>

    <script>

        <div class="mt-1.5">
    <div class="w-full bg-slate-200 rounded-full h-1.5">
        <div id="strength-bar" class="h-1.5 rounded-full transition-all duration-300 w-0"></div>
    </div>
    <span id="strength-text" class="text-xs mt-1"></span>
</div>

    const passwordInput = document.querySelector('input[name="password"]');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
            const val = this.value;
            let score = 0;

            if (val.length >= 8)          score++;
            if (/[A-Z]/.test(val))        score++;
            if (/[a-z]/.test(val))        score++;
            if (/[0-9]/.test(val))        score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['bg-red-500', 'bg-red-400', 'bg-amber-400', 'bg-emerald-400', 'bg-emerald-500'];
            const labels = ['Muy débil', 'Débil', 'Regular', 'Buena', 'Muy segura'];
            const widths = ['w-1/5', 'w-2/5', 'w-3/5', 'w-4/5', 'w-full'];

            if (strengthBar && score > 0) {
                strengthBar.className = `h-1.5 rounded-full transition-all duration-300 ${colors[score - 1]} ${widths[score - 1]}`;
                strengthText.textContent = labels[score - 1];
                strengthText.className = `text-xs mt-1 ${score <= 2 ? 'text-red-500' : score === 3 ? 'text-amber-500' : 'text-emerald-600'}`;
            } else if (strengthBar) {
                strengthBar.className = 'h-1.5 rounded-full w-0';
                strengthText.textContent = '';
            }
        });
    }
</script>

</body>
</html>