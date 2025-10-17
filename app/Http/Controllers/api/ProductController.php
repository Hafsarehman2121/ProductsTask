<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\ProductRepoInterface;
use App\Http\Requests\ProductRequests\CreateProductRequest;
use App\Http\Requests\ProductRequests\UpdateProductRequest;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepoInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of all products
     */
    public function index()
    {
        try {
            $products = $this->productRepository->allProducts();

            return response()->json([
                'message' => 'Products retrieved successfully',
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created product
     */
    public function store(CreateProductRequest $request)
    {
        try {
            if ($request->hasFile('image')) {
                $request['image'] = $request->file('image');
            }

            return $this->productRepository->createProduct($request->validated());

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        try {
            $product = $this->productRepository->findProduct($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            return response()->json([
                'message' => 'Product retrieved successfully',
                'product' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified product
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            if ($request->hasFile('image')) {
                $request['image'] = $request->file('image');
            }

            $product = $this->productRepository->updateProduct($id, $request->validated());
            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        try {
            $product = $this->productRepository->deleteProduct($id);
            if(!$product){
                return response()->json([
                'status' => false,
                'message' => 'Product not found',
                ], 404);
            }
            return response()->json(['message' => 'Product deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

