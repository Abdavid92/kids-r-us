<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Siembra los roles y permisos predeterminados en la base de datos.
        $this->call(RolesAndPermissionsSeeder::class);
        //Siembra las categorías predeterminadas en la base de datos.
        $this->call(CategoriesSeeder::class);
        //Siembra productos de muestra.
        $this->call(ProductsSeeder::class);
    }
}
