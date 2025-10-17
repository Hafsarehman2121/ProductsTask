<?php

namespace App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces;

interface ProductRepoInterface
{
    public function allProducts();
    public function findProduct($id);
    public function createProduct(array $data);
    public function updateProduct($id, array $data);
    public function deleteProduct($id);
}
