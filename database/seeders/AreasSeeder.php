<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Areas;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //   Areas::factory(50)->create();

        Areas::factory()->create([
            'area_name' => 'Kajiado',
            'description' => 'In Kenya',
        ]);
    }
}
