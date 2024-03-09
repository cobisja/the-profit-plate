<?php

declare(strict_types=1);

namespace App\Service\Admin\ProductType;

use App\Entity\ProductType;
use App\Exception\ProductType\ProductTypeNotFoundException;
use App\Repository\ProductTypeRepository;

readonly class ProductTypeShowService
{
    public function __construct(private ProductTypeRepository $productTypeRepository)
    {
    }

    /**
     * @throws ProductTypeNotFoundException
     */
    public function execute(string $id): ProductType
    {
        /** @var ProductType $productType */
        if (!$productType = $this->productTypeRepository->find($id)) {
            throw new ProductTypeNotFoundException();
        }

        return $productType;
    }
}
