<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categorie = Categories::create([
            'slug' => 'accesorios',
            'parent_id' => 0,
            'level' => 1,
            'title' => 'ACCESORIOS',
            'description' => '',
            'main_image' => '/storage/imagenes/oMQLyglUkwH9CkoMbmFnF1AkNqa8rH2CRghrRLWH.svg',
            'discount' => 0.00,
            'position' => 1,
            'featured' => 0,
            'active' => 1
        ]);

        $categorie = Categories::create([
            'slug' => 'cables',
            'parent_id' => 1,
            'level' => 2,
            'title' => 'CABLES',
            'description' => '',
            'main_image' => '',
            'discount' => 0.00,
            'position' => 2,
            'featured' => 0,
            'active' => 1
        ]);

        $categorie = Categories::create([
            'slug' => 'ofertas',
            'parent_id' => 0,
            'level' => 1,
            'title' => 'OFERTAS',
            'description' => '',
            'main_image' => '/storage/imagenes/W1YEl98pkn9OFxmv8waXduibcYKQUccxW34LkN5Y.svg',
            'discount' => 0.00,
            'position' => 3,
            'featured' => 0,
            'active' => 1
        ]);
    }
}
