<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Career;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $careers = [            
            ['name' => 'Ingeniería Industrial'],
            ['name' => 'Ingeniería en Sistemas Computacionales'],            
            ['name' => 'Ingeniería en Electrónica'],
            ['name' => 'Ingeniería en Gestión Empresarial'],
            ['name' => 'Ingeniería en Innovación Agrícola Sustentable'],            
            ['name' => 'Ingeniería Petrolera'],
            ['name' => 'Ingeniería en informática'],
            ['name' => 'Ingenieria en Recursos Renovables'],            
            ['name' => 'Contador Público']
        ];

        Career::insert($careers);
    }
}
