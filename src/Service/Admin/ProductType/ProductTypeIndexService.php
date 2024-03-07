<?php

declare(strict_types=1);

namespace App\Service\Admin\ProductType;

use App\Entity\ProductType;
use App\Repository\ProductTypeRepository;

readonly class ProductTypeIndexService
{
    public function __construct(private ProductTypeRepository $productTypeRepository)
    {
    }

    /**
     * @return ProductType[]
     */
    public function execute(): array
    {
        return $this->productTypeRepository->findBy(criteria: [], orderBy: ['name' => 'ASC']);
    }
}