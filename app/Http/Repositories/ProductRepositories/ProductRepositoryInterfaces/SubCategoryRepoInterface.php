<?php

namespace App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces;

interface SubCategoryRepoInterface
{
    public function allSubCategories();
    public function findSubCategory($id);
    public function createSubCategory(array $data);
    public function updateSubCategory($id, array $data);
    public function deleteSubCategory($id);
}
