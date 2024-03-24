<?php

declare(strict_types=1);

namespace App\Service\Admin\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;

readonly class ProductIndexService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * @return Product[]
     */
    public function execute(): array
    {
        return $this->productRepository->getAllWithType();
    }
}