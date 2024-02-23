<?php

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
        return $this->recipeTypeRepository->findAll();
    }
}