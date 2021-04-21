<?php

namespace Database\Seeders;

use App\Models\Brands;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $brand = Brands::create([
            'title' => 'PRODUCTO',
            'position' => 1,
            'active' => 1
        ]);
    }
}
