<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('roles')->insert([
            ['id_rol' => 1, 'nombre' => 'Administrador', 'descripcion' => 'Control total del sistema'],
            ['id_rol' => 2, 'nombre' => 'Usuario', 'descripcion' => 'Usuario regular del sistema'],
        ]);
    }
}
