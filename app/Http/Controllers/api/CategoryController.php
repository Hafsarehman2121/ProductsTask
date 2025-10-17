<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\CategoryRepoInterface;
use App\Http\Requests\CategoryRequests\CreateCategoryRequest;
use App\Http\Requests\CategoryRequests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class CategoryController extends Controller
{
    protected $categoryRepo;

    /**
     * Inject Category Repository Interface
     */
    public function __construct(CategoryRepoInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Display a listing of all categories
     */
    public function index()
    {
        try {
            $categories = $this->categoryRepo->allCategories();

            return response()->json([
                'status' => true,
                'message' => 'All Categories:',
                'categories' => $categories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created category
     */
    public function store(CreateCategoryRequest $request)
    {
        try {
            $category = $this->categoryRepo->createCategory($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Category Added Successfully.',
                'category' => $category
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific category by its ID
     */
    public function show($id)
    {
        try {
            $category = $this->categoryRepo->findCategory($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Category retrieved successfully.',
                'category' => $category
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing category
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $category = $this->categoryRepo->updateCategory($id, $request->validated());

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Category Updated Successfully.',
                'category' => $category
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        try {
            $category = $this->categoryRepo->deleteCategory($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
