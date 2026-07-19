<?php

namespace Database\Seeders;

use App\Models\Ciudad;
use Illuminate\Database\Seeder;

class CiudadesSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            ['nombre' => 'Asunción',               'departamento' => 'Capital'],
            ['nombre' => 'San Lorenzo',             'departamento' => 'Central'],
            ['nombre' => 'Luque',                   'departamento' => 'Central'],
            ['nombre' => 'Capiatá',                 'departamento' => 'Central'],
            ['nombre' => 'Lambaré',                 'departamento' => 'Central'],
            ['nombre' => 'Fernando de la Mora',     'departamento' => 'Central'],
            ['nombre' => 'Limpio',                  'departamento' => 'Central'],
            ['nombre' => 'Ñemby',                   'departamento' => 'Central'],
            ['nombre' => 'Mariano Roque Alonso',    'departamento' => 'Central'],
            ['nombre' => 'Itauguá',                 'departamento' => 'Central'],
            ['nombre' => 'Villa Elisa',             'departamento' => 'Central'],
            ['nombre' => 'Guarambaré',              'departamento' => 'Central'],
            ['nombre' => 'Ciudad del Este',         'departamento' => 'Alto Paraná'],
            ['nombre' => 'Presidente Franco',       'departamento' => 'Alto Paraná'],
            ['nombre' => 'Encarnación',             'departamento' => 'Itapúa'],
            ['nombre' => 'Coronel Oviedo',          'departamento' => 'Caaguazú'],
            ['nombre' => 'Pedro Juan Caballero',    'departamento' => 'Amambay'],
            ['nombre' => 'Concepción',              'departamento' => 'Concepción'],
            ['nombre' => 'Villarrica',              'departamento' => 'Guairá'],
            ['nombre' => 'Caacupé',                 'departamento' => 'Cordillera'],
        ];

        foreach ($ciudades as $c) {
            Ciudad::firstOrCreate(
                ['nombre' => $c['nombre'], 'departamento' => $c['departamento'], 'pais' => 'Paraguay'],
                ['activo' => true]
            );
        }

        $this->command->info('Ciudades de Paraguay sembradas.');
    }
}
