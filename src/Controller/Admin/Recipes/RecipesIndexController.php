<?php

declare(strict_types=1);

namespace App\Controller\Admin\Recipes;

use App\Service\Admin\Recipes\RecipeIndexService;
use App\Service\Admin\RecipeType\RecipeTypeIndexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipesIndexController extends AbstractController
{
    public function __construct(
        private readonly RecipeIndexService $recipeIndexService,
        private readonly RecipeTypeIndexService $recipeTypeIndexService
    ) {
    }

    #[Route('/recipes', name: 'app_admin_recipes_index', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $recipes = $this->recipeIndexService->execute();
        $recipeTypes = $this->recipeTypeIndexService->execute();

        return $this->render('admin/recipes/index.html.twig', compact('recipes', 'recipeTypes'));
    }
}