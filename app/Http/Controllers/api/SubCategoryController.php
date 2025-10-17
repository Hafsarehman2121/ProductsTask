<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\SubCategoryRepoInterface;
use App\Http\Requests\SubCategoryRequests\CreateSubCategoryRequest;
use App\Http\Requests\SubCategoryRequests\UpdateSubCategoryRequest;
use Illuminate\Validation\ValidationException;
use Exception;

class SubCategoryController extends Controller
{
    protected $subCategoryRepo;

    /**
     * Inject SubCategory Repository Interface
     */
    public function __construct(SubCategoryRepoInterface $subCategoryRepo)
    {
        $this->subCategoryRepo = $subCategoryRepo;
    }

    /**
     * Display a listing of all subcategories
     */
    public function index()
    {
        try {
            $subCategories = $this->subCategoryRepo->allSubCategories();

            return response()->json([
                'status' => true,
                'message' => 'All Sub-Categories:',
                'sub_categories' => $subCategories
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching sub-categories.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created subcategory
     */
    public function store(CreateSubCategoryRequest $request)
    {
        try {
            $subCategory = $this->subCategoryRepo->createSubCategory($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Sub-Category Added Successfully.',
                'sub_category' => $subCategory
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
                'message' => 'Error creating sub-category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific subcategory by ID
     */
    public function show($id)
    {
        try {
            $subCategory = $this->subCategoryRepo->findSubCategory($id);

            if (!$subCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sub-Category not found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Sub-Category retrieved successfully.',
                'sub_category' => $subCategory
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching sub-category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing subcategory
     */
    public function update(UpdateSubCategoryRequest $request, $id)
    {
        try {
            $subCategory = $this->subCategoryRepo->updateSubCategory($id, $request->validated());

            if (!$subCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sub-Category not found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Sub-Category Updated Successfully.',
                'sub_category' => $subCategory
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
                'message' => 'Error updating sub-category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a subcategory by ID
     */
    public function destroy($id)
    {
        try {
            $subCategory = $this->subCategoryRepo->deleteSubCategory($id);

            if (!$subCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sub-Category not found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Sub-Category deleted successfully.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting sub-category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
