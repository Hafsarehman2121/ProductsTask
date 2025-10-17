<?php
namespace App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces;

interface CategoryRepoInterface
{
    public function allCategories();
    public function findCategory($id);
    public function createCategory(array $data);
    public function updateCategory($id, array $data);
    public function deleteCategory($id);
}
