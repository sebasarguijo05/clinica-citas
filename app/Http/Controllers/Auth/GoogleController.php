<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirect(GoogleCalendarService $google)
    {
        return redirect($google->getAuthUrl());
    }

    public function callback(Request $request, GoogleCalendarService $google)
    {
        if ($request->has('error')) {
            return redirect()->back()->with('error', 'No se pudo conectar con Google Calendar.');
        }

        $token = $google->getTokenFromCode($request->code);

        $user = auth()->user();
        $user->update([
            'google_token'         => json_encode($token),
            'google_refresh_token' => $token['refresh_token'] ?? $user->google_refresh_token,
        ]);

        $redirectTo = $user->isAdmin()
            ? route('admin.dashboard')
            : route('dashboard');

        return redirect($redirectTo)->with('success', 'Google Calendar conectado exitosamente.');
    }

    public function disconnect()
    {
        auth()->user()->update([
            'google_token'         => null,
            'google_refresh_token' => null,
        ]);

        $redirectTo = auth()->user()->isAdmin()
            ? route('admin.dashboard')
            : route('dashboard');

        return redirect($redirectTo)->with('success', 'Google Calendar desconectado correctamente.');
    }
}