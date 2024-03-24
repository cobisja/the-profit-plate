<?php

declare(strict_types=1);

namespace App\Service\Admin\Product;

use App\Entity\Product;
use App\Exception\Product\ProductNotFoundException;
use App\Repository\ProductRepository;

readonly class ProductShowService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * @throws ProductNotFoundException
     */
    public function execute(string $id): Product
    {
        if (!$product = $this->productRepository->findByIdWithItsType($id)) {
            throw new ProductNotFoundException();
        }

        return $product;
    }
}