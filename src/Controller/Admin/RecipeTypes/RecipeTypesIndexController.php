<?php

declare(strict_types=1);

namespace App\Controller\Admin\RecipeTypes;

use App\Service\Admin\RecipeType\RecipeTypeIndexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipeTypesIndexController extends AbstractController
{
    public function __construct(private readonly RecipeTypeIndexService $recipeTypeIndexService)
    {
    }

    #[Route('/recipe_types', name: 'app_admin_recipe_types_index', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $recipeTypes = $this->recipeTypeIndexService->execute();

        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/recipe_types/_recipe-types.html.twig', compact('recipeTypes'));
        }

        return $this->render('admin/recipe_types/index.html.twig', compact('recipeTypes'));
    }
}