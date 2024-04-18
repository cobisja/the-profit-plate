<?php

declare(strict_types=1);

namespace App\Controller\Admin\Recipes;

use App\Entity\Recipe;
use App\Exception\Recipe\RecipeNotFoundException;
use App\Exception\Shared\InvalidPictureException;
use App\Exception\Shared\PictureNotUploadedException;
use App\Form\RecipeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipesNewController extends AbstractController
{
    public function __construct(
        private readonly IngredientsCollectionUpdater $ingredientsCollectionUpdater,
        private readonly RecipeImageUpdater $recipeImageUpdater,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/recipes/new', name: 'app_admin_recipes_new', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        try {
            $recipe = new Recipe();
            $form = $this->createForm(RecipeFormType::class, $recipe);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->ingredientsCollectionUpdater->execute($form->get('ingredients')->getData()->toArray());
                $this->recipeImageUpdater->execute($form->get('picture')->getData(), $recipe);

                $recipe->updateUpdatedAt();

                $this->entityManager->flush();
                $this->addFlash('success', 'Recipe added');

                return $this->redirectToRoute('app_admin_recipes_index', ['id' => (string)$recipe->getId()]);
            }
        } catch (RecipeNotFoundException|InvalidPictureException|PictureNotUploadedException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_recipes_index');
        }

        return $this->render('admin/recipes/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ]);
    }
}
