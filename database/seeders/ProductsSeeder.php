<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::create([
            'name' => 'Blue Blouse',
            'price' => 22.00,
            'stock' => 15,
            'tags' => ['Blouse', 'Girls'],
            'description' => fake()->paragraph(),
            'additional_information' => [
                'color' => 'blue',
                'material' => 'cotton',
                'age' => '6 years'
            ],
            'category_id' => Category::query()->where('name', 'For Girls')->first()->id
        ]);

        Product::create([
           'name' => 'Rainbow Rainy Bag',
            'price' => 15.00,
            'stock' => 9,
            'tags' => ['Home'],
            'description' => fake()->paragraph(),
            'additional_information' => [
                'color' => 'orange',
                'material' => 'cotton'
            ],
            'category_id' => Category::query()->where('name', 'For Home')->first()->id
        ]);

        Product::create([
            'name' => 'Pattern Shirt',
            'price' => 28.00,
            'stock' => 10,
            'tags' => ['Boys'],
            'description' => fake()->paragraph(),
            'additional_information' => [
                'color' => 'Sky blue',
                'material' => 'cotton',
                'age' => '6 years'
            ],
            'category_id' => Category::query()->where('name', 'For Boys')->first()->id
        ]);

        Product::create([
           'name' => 'White Blouse',
           'price' => 17.00,
           'stock' => 0,
           'tags' => ['Girls', 'Blouse'],
           'description' => fake()->paragraph(),
           'additional_information' => [
               'color' => 'white',
               'material' => 'cotton',
               'age' => '5 years'
           ],
            'category_id' => Category::query()->where('name', 'For Girls')->first()->id
        ]);
    }
}
