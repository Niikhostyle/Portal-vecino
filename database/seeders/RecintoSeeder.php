<?php

namespace Database\Seeders;

use App\Models\Recinto;
use Illuminate\Database\Seeder;

class RecintoSeeder extends Seeder
{
    public function run(): void
    {
        // Recintos municipales
        Recinto::create([
            'tipo' => 'salon',
            'nombre' => 'Salón Municipal Principal',
            'descripcion' => 'Salón principal para eventos y reuniones',
            'activo' => true,
        ]);

        Recinto::create([
            'tipo' => 'teatro',
            'nombre' => 'Teatro Municipal',
            'descripcion' => 'Teatro municipal para presentaciones y eventos culturales',
            'activo' => true,
        ]);

        Recinto::create([
            'tipo' => 'espacio_comunitario',
            'nombre' => 'Espacio Comunitario Centro',
            'descripcion' => 'Espacio comunitario para actividades vecinales',
            'activo' => true,
        ]);

        // Recintos deportivos
        Recinto::create([
            'tipo' => 'deportivo',
            'nombre' => 'Cancha Municipal Principal',
            'descripcion' => 'Cancha principal para actividades deportivas',
            'activo' => true,
        ]);

        Recinto::create([
            'tipo' => 'deportivo',
            'nombre' => 'Gimnasio Municipal',
            'descripcion' => 'Gimnasio municipal cubierto',
            'activo' => true,
        ]);
    }
}
