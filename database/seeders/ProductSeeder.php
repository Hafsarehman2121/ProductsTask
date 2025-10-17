<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\SubCategory;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $subcategories = SubCategory::all();

        $subcategories->each(function ($subcategory) {
            Product::factory(5)->create([
                'sub_category_id' => $subcategory->id,
            ]);
        });
    }
}
