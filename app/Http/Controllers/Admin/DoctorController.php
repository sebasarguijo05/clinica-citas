<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
       $doctors = Doctor::withTrashed()->orderBy('name')->paginate(10);
    return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[\pL\s\.\-]+$/u',
        ],
        'specialty' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[\pL\s\/\-]+$/u',
        ],
        'email' => [
            'required',
            'email:rfc',
            'max:255',
            'unique:doctors,email',
        ],
        'phone' => [
            'nullable',
            'string',
            'regex:/^[\+\d\s\-\(\)]{7,20}$/', // Formato teléfono internacional
        ],
        'bio' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ], [
        'name.required'       => 'El nombre es obligatorio.',
        'name.min'            => 'El nombre debe tener al menos 3 caracteres.',
        'name.regex'          => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
        'specialty.required'  => 'La especialidad es obligatoria.',
        'specialty.regex'     => 'La especialidad contiene caracteres no permitidos.',
        'email.required'      => 'El correo es obligatorio.',
        'email.email'         => 'Ingresa un correo válido.',
        'email.unique'        => 'Este correo ya está registrado.',
        'phone.regex'         => 'El teléfono tiene un formato inválido. Ej: +504 9999-0001',
    ]);

    $validated['active'] = $request->has('active');

    Doctor::create($validated);

    return redirect()->route('admin.doctors.index')
        ->with('success', 'Doctor creado exitosamente.');
}

    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
{
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[\pL\s\.\-]+$/u',
        ],
        'specialty' => [
            'required',
            'string',
            'min:3',
            'max:100',
            'regex:/^[\pL\s\/\-]+$/u',
        ],
        'email' => [
            'required',
            'email:rfc',
            'max:255',
            'unique:doctors,email,' . $doctor->id,
        ],
        'phone' => [
            'nullable',
            'string',
            'regex:/^[\+\d\s\-\(\)]{7,20}$/',
        ],
        'bio' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ], [
        'name.required'       => 'El nombre es obligatorio.',
        'name.regex'          => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
        'specialty.required'  => 'La especialidad es obligatoria.',
        'specialty.regex'     => 'La especialidad contiene caracteres no permitidos.',
        'email.required'      => 'El correo es obligatorio.',
        'email.email'         => 'Ingresa un correo válido.',
        'email.unique'        => 'Este correo ya está registrado por otro doctor.',
        'phone.regex'         => 'El teléfono tiene un formato inválido. Ej: +504 9999-0001',
    ]);

    $validated['active'] = $request->has('active');

    $doctor->update($validated);

    return redirect()->route('admin.doctors.index')
        ->with('success', 'Doctor actualizado exitosamente.');
}

    // Deshabilitar doctor (soft delete)
public function destroy(Doctor $doctor)
{
    $doctor->update(['active' => false]);
    $doctor->delete();

    return redirect()->route('admin.doctors.index')
        ->with('success', 'Doctor deshabilitado correctamente.');
}

// Restaurar doctor
public function restore(int $id)
{
    $doctor = Doctor::withTrashed()->findOrFail($id);
    $doctor->restore();
    $doctor->update(['active' => true]);

    return redirect()->route('admin.doctors.index')
        ->with('success', 'Doctor restaurado correctamente.');
}
}