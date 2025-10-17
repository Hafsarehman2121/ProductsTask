<?php

namespace App\Http\Repositories\ProductRepositories;

use App\Models\SubCategory;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\SubCategoryRepoInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class SubCategoryRepo implements SubCategoryRepoInterface
{
    /**
     * Retrieve all subcategories with their related category and products.
     *
     * @return \Illuminate\Database\Eloquent\Collection|false
     */
    public function allSubCategories()
    {
        try {
            return SubCategory::with(['category', 'products'])->get();
        } catch (Exception $e) {
            Log::error('Error fetching subcategories: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Find a specific subcategory by ID.
     *
     * @param  int  $id
     * @return SubCategory|false
     */
    public function findSubCategory($id)
    {
        try {
            $subCategory = SubCategory::with(['category', 'products'])->find($id);
            return $subCategory ?: false;
        } catch (Exception $e) {
            Log::error("Error finding subcategory ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new subcategory.
     *
     * @param  array  $data
     * @return SubCategory|false
     */
    public function createSubCategory(array $data)
    {
        try {
            $subCategory = SubCategory::create($data);
            return $subCategory ?: false;
        } catch (Exception $e) {
            Log::error('Error creating subcategory: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing subcategory.
     *
     * @param  int  $id
     * @param  array  $data
     * @return SubCategory|false
     */
    public function updateSubCategory($id, array $data)
    {
        try {
            $subCategory = SubCategory::find($id);
            if (!$subCategory) {
                return false;
            }

            $subCategory->update($data);
            return $subCategory;
        } catch (Exception $e) {
            Log::error("Error updating subcategory ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a subcategory by ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteSubCategory($id)
    {
        try {
            $subCategory = SubCategory::find($id);
            if (!$subCategory) {
                return false;
            }

            $subCategory->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error deleting subcategory ID {$id}: " . $e->getMessage());
            return false;
        }
    }
}
