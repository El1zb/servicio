<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campus;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campuses = [
            ['name' => 'ITS Cosamaloapan'],
            ['name' => 'ITSCO Campus CD. Alemán'],
            ['name' => 'ITSCO Extensión Carlos A. Carrillo'],
            ['name' => 'ITSCO Extensión Otatitlán']
        ];

        Campus::insert($campuses);
    }
}
