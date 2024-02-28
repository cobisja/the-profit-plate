<?php

declare(strict_types=1);

namespace App\Service\Admin\RecipeType;

use App\Entity\RecipeType;
use App\Repository\RecipeTypeRepository;

readonly class RecipeTypeIndexService
{
    public function __construct(private RecipeTypeRepository $recipeTypeRepository)
    {
    }

    /**
     * @return RecipeType[]
     */
    public function execute(): array
    {
        return $this->recipeTypeRepository->findBy(criteria: [], orderBy: ['name' => 'ASC']);
    }
}