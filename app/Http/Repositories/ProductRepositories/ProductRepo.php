<?php

namespace App\Http\Repositories\ProductRepositories;

use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\ProductRepoInterface;
use App\Models\Product;
use App\Models\SubCategory;
use App\Traits\ImageUploadTrait;
use Exception;

class ProductRepo implements ProductRepoInterface
{
    use ImageUploadTrait;

    /**
     * Retrieve all products with their subcategory relationship
     */
    public function allProducts()
    {
        return Product::with('subcategory.category')->get();
    }

    /**
     * Find a specific product by its ID with subcategory details
     */
    public function findProduct($id)
    {
        $product = Product::with('subcategory.category')->find($id);
        if(!$product){
            return false;
        }

        return $product;
    }

    /**
     * Create a new product with optional image upload
     */

    public function createProduct(array $data)
    {
        try {
            $subcategory = SubCategory::find($data['sub_category_id']);

            if (!$subcategory) {
                return false;
            }
            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                $data['image'] = $this->uploadImage($data['image'], 'products');
            }

            $product = Product::create($data);

            return $product;

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing product by its ID
     */
    public function updateProduct($id, array $data)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return false;
            }

            if (isset($data['subcategory_id'])) {
                $subcategory = SubCategory::find($data['sub_category_id']);
                if (!$subcategory) {
                    return false;
                }
            }

            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                $this->deleteImage($product->image);
                $data['image'] = $this->uploadImage($data['image'], 'products');
            }

            $product->update($data);

            return $product;

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product by its ID
     */
    public function deleteProduct($id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return false;
            }

            $this->deleteImage($product->image);
            $product->delete();

            return true;

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
