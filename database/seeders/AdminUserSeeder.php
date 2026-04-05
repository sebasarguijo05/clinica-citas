<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el Admin
        User::updateOrCreate(
            ['email' => 'admin@clinica.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Crear el Paciente
        User::updateOrCreate(
            ['email' => 'paciente@clinica.com'],
            [
                'name'     => 'Paciente Demo',
                'password' => Hash::make('password'),
                'role'     => 'patient',
            ]
        );

        // Crear Doctores
        Doctor::updateOrCreate(
            ['email' => 'carlos@clinica.com'],
            [
                'name'      => 'Dr. Carlos Martínez',
                'specialty' => 'Odontología General',
                'phone'     => '+504 9999-0001',
                'bio'       => 'Especialista en odontología con 10 años de experiencia.',
                'active'    => true,
            ]
        );

        Doctor::updateOrCreate(
            ['email' => 'ana@clinica.com'],
            [
                'name'      => 'Dra. Ana López',
                'specialty' => 'Ortodoncia',
                'phone'     => '+504 9999-0002',
                'bio'       => 'Especialista en ortodoncia y brackets.',
                'active'    => true,
            ]
        );
    }
}