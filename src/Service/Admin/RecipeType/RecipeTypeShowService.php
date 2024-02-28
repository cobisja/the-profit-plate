<?php

declare(strict_types=1);

namespace App\Service\Admin\RecipeType;

use App\Entity\RecipeType;
use App\Exception\RecipeType\RecipeTypeNotFoundException;
use App\Repository\RecipeTypeRepository;

readonly class RecipeTypeShowService
{
    public function __construct(private RecipeTypeRepository $recipeTypeRepository)
    {
    }

    /**
     * @throws RecipeTypeNotFoundException
     */
    public function execute(string $id): RecipeType
    {
        /** @var RecipeType $recipeType */
        if (!$recipeType = $this->recipeTypeRepository->find($id)) {
            throw new RecipeTypeNotFoundException();
        }

        return $recipeType;
    }
}
