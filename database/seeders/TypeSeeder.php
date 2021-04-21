<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Types;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $type = Types::create([
            'title' => 'VENTAS', 
            'position' => 1,
            'active' => 1
        ]);
    }
}
