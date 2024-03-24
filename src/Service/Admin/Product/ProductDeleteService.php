<?php

declare(strict_types=1);

namespace App\Service\Admin\Product;

use App\Exception\Product\ProductNotFoundException;
use App\Repository\ProductRepository;

readonly class ProductDeleteService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * @throws ProductNotFoundException
     */
    public function execute(string $id): void
    {
        if (!$product = $this->productRepository->find($id)) {
            throw new ProductNotFoundException();
        }

        $this->productRepository->remove($product);
    }
}
