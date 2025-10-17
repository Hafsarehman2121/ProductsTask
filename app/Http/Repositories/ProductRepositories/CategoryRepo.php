<?php

namespace App\Http\Repositories\ProductRepositories;

use App\Models\Category;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\CategoryRepoInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryRepo implements CategoryRepoInterface
{
    /**
     * Retrieve all categories with their subcategories.
     *
     * @return \Illuminate\Database\Eloquent\Collection|false
     */
    public function allCategories()
    {
        try {
            return Category::with('subCategories')->get();
        } catch (Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find a specific category by its ID.
     *
     * @param  int  $id
     * @return Category|false
     */
    public function findCategory($id)
    {
        try {
            $category = Category::with('subCategories')->find($id);
            if (!$category) {
                return false;
            }
            return $category;
        } catch (Exception $e) {
            Log::error("Error finding category ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new category.
     *
     * @param  array  $data
     * @return Category|false
     */
    public function createCategory(array $data)
    {
        try {
            $category = Category::create($data);
            return $category ?: false;
        } catch (Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing category by ID.
     *
     * @param  int  $id
     * @param  array  $data
     * @return Category|false
     */
    public function updateCategory($id, array $data)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return false;
            }

            $category->update($data);
            return $category;
        } catch (Exception $e) {
            Log::error("Error updating category ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a category by ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteCategory($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return false;
            }

            $category->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error deleting category ID {$id}: " . $e->getMessage());
            return false;
        }
    }
}
