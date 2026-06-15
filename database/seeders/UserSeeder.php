<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Nicolás Álvarez',
            'email' => 'nalvarez@chanco.cl',
            'password' => Hash::make('admin123'),
            'rol' => 'administrador',
            'estado' => 'activo',
        ]);

        // Oficina de Partes
        User::create([
            'name' => 'Oficina de Partes',
            'email' => 'oficinapartes@chanco.cl',
            'password' => Hash::make('op123'),
            'rol' => 'oficina_partes',
            'estado' => 'activo',
        ]);

        // Funcionario
        User::create([
            'name' => 'Funcionario Ejemplo',
            'email' => 'funcionario1@chanco.cl',
            'password' => Hash::make('funcionario123'),
            'rol' => 'funcionario',
            'estado' => 'activo',
        ]);

        // Vecino
        User::create([
            'name' => 'Vecino Ejemplo',
            'email' => 'vecino1@gmail.com',
            'password' => Hash::make('vecino123'),
            'rol' => 'vecino',
            'estado' => 'activo',
        ]);
    }
}
