<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
   
        Doctor::create([
        'name'      => 'Dr. Carlos Martínez',
        'specialty' => 'Odontología General',
        'email'     => 'carlos@clinica.com',
        'phone'     => '+504 9999-0001',
        'bio'       => 'Especialista en odontología con 10 años de experiencia.',
        'active'    => true,
        ]);

        Doctor::create([
            'name'      => 'Dra. Ana López',
            'specialty' => 'Ortodoncia',
            'email'     => 'ana@clinica.com',
            'phone'     => '+504 9999-0002',
            'active'    => true,
        ]);
    }

    
}
