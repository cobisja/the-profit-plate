<?php

declare(strict_types=1);

namespace App\Service\Admin\RecipeType;

use App\Exception\RecipeType\RecipeTypeNotFoundException;
use App\Repository\RecipeTypeRepository;

readonly class RecipeTypeDeleteService
{
    public function __construct(private RecipeTypeRepository $recipeTypeRepository)
    {
    }

    /**
     * @throws RecipeTypeNotFoundException
     */
    public function execute(string $id): void
    {
        if (!$recipeType = $this->recipeTypeRepository->find($id)) {
            throw new RecipeTypeNotFoundException();
        }

        $this->recipeTypeRepository->remove($recipeType);
    }
}