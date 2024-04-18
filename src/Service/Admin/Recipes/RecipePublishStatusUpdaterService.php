<?php

declare(strict_types=1);

namespace App\Service\Admin\Recipes;

use App\Exception\Recipe\RecipeNotFoundException;
use App\Repository\RecipeRepository;

readonly class RecipePublishStatusUpdaterService
{
    public function __construct(private RecipeRepository $recipeRepository)
    {
    }

    /**
     * @throws RecipeNotFoundException
     */
    public function execute(string $id, bool $published): void
    {
        if (!$recipe = $this->recipeRepository->find($id)) {
            throw new RecipeNotFoundException();
        }

        $recipe->setPublished($published);

        $this->recipeRepository->save($recipe);
    }
}