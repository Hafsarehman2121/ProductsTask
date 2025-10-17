<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();

        $categories->each(function ($category) {
            SubCategory::factory(3)->create([
                'category_id' => $category->id,
            ]);
        });
    }
}
