<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\MessageController as PatientMessageController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ─── PACIENTES ────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Citas (sin mensajes)
    Route::resource('appointments', PatientAppointmentController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy'])
        ->names('patient.appointments');

    // Mensajería independiente
    Route::get('messages', [PatientMessageController::class, 'index'])
        ->name('patient.messages.index');
    Route::get('messages/{appointment}', [PatientMessageController::class, 'show'])
        ->name('patient.messages.show');
    Route::post('messages/{appointment}', [PatientMessageController::class, 'store'])
        ->name('patient.messages.store');

    // Calendario
    Route::get('calendar', [\App\Http\Controllers\Patient\CalendarController::class, 'index'])
        ->name('patient.calendar');
    Route::get('calendar/events', [\App\Http\Controllers\Patient\CalendarController::class, 'events'])
        ->name('patient.calendar.events');
});

// ─── ADMINISTRADORES ─────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::patch('appointments/{appointment}/cancel',
    [AdminAppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Doctores
    Route::resource('doctors', DoctorController::class);

    Route::patch('doctors/{id}/restore',
    [\App\Http\Controllers\Admin\DoctorController::class, 'restore'])
    ->name('doctors.restore');

    // Citas
    Route::resource('appointments', AdminAppointmentController::class)
        ->only(['index', 'show']);
    Route::patch('appointments/{appointment}/approve',
        [AdminAppointmentController::class, 'approve'])->name('appointments.approve');
    Route::patch('appointments/{appointment}/reject',
        [AdminAppointmentController::class, 'reject'])->name('appointments.reject');
    Route::patch('appointments/{appointment}/reschedule',
        [AdminAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
    Route::patch('appointments/{appointment}/notes',
        [AdminAppointmentController::class, 'saveNotes'])->name('appointments.notes');

    // Mensajería independiente
    Route::get('messages', [AdminMessageController::class, 'index'])
        ->name('messages.index');
    Route::get('messages/{appointment}', [AdminMessageController::class, 'show'])
        ->name('messages.show');
    Route::post('messages/{appointment}', [AdminMessageController::class, 'store'])
        ->name('messages.store');

    // Calendario
    Route::get('calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])
        ->name('calendar');
    Route::get('calendar/events', [\App\Http\Controllers\Admin\CalendarController::class, 'events'])
        ->name('calendar.events');
});

// ─── Google Calendar OAuth ────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirect'])
        ->name('google.auth');
    Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'callback'])
        ->name('google.callback');
    Route::post('auth/google/disconnect', [\App\Http\Controllers\Auth\GoogleController::class, 'disconnect'])
        ->name('google.disconnect');
});



require __DIR__.'/auth.php';