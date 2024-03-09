<?php

declare(strict_types=1);

namespace App\Service\Admin\ProductType;

use App\Exception\ProductType\ProductTypeNotFoundException;
use App\Repository\ProductTypeRepository;

readonly class ProductTypeDeleteService
{
    public function __construct(private ProductTypeRepository $productTypeRepository)
    {
    }

    /**
     * @throws ProductTypeNotFoundException
     */
    public function execute(string $id): void
    {
        if (!$productType = $this->productTypeRepository->find($id)) {
            throw new ProductTypeNotFoundException();
        }

        $this->productTypeRepository->remove($productType);
    }
}