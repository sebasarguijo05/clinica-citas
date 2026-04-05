<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\-]+$/u', // Solo letras, espacios y guiones
            ],
            'email'    => [
                'required',
                'string',
                'email:rfc,dns',          // Validación RFC + DNS real
                'max:255',
                'unique:'.User::class,
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()           // Al menos una letra
                    ->mixedCase()         // Mayúsculas y minúsculas
                    ->numbers()           // Al menos un número
                    ->symbols()           // Al menos un símbolo (!@#$...)
                    ->uncompromised(),    // No debe estar en filtraciones conocidas
            ],
        ], [
            // Mensajes personalizados en español
            'name.required'    => 'El nombre es obligatorio.',
            'name.min'         => 'El nombre debe tener al menos 3 caracteres.',
            'name.max'         => 'El nombre no puede exceder 100 caracteres.',
            'name.regex'       => 'El nombre solo puede contener letras, espacios y guiones.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'Ingresa un correo electrónico válido.',
            'email.unique'     => 'Este correo ya está registrado.',
            'password.required'=> 'La contraseña es obligatoria.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name'     => strip_tags(trim($request->name)),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role'     => 'patient',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard'));
    }
}