<?php

declare(strict_types=1);

namespace App\Controller\Admin\RecipeTypes;

use App\Service\Admin\RecipeType\RecipeTypeIndexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipeTypesIndexController extends AbstractController
{
    #[Route('/recipe_types', name: 'app_admin_recipe_types_index', methods: ['GET'])]
    public function __invoke(RecipeTypeIndexService $recipeTypeIndexService): Response
    {
        $recipeTypes = $recipeTypeIndexService->execute();

        return $this->render('admin/recipe_types/index.html.twig', compact('recipeTypes'));
    }
}