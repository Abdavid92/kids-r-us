<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    private array $defaultCategories = [
        'For Babies',
        'For Boys',
        'For Girls',
        'For Home',
        'For Play'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->defaultCategories as $category) {

            Category::query()->create([
                'name' => $category
            ]);
        }
    }
}
