<?php

declare(strict_types=1);

namespace App\Controller\Admin\Recipes;

use App\Exception\Recipe\RecipeNotFoundException;
use App\Exception\Shared\InvalidPictureException;
use App\Exception\Shared\PictureNotUploadedException;
use App\Form\RecipeFormType;
use App\Service\Admin\Recipes\RecipeShowService;
use App\Service\Admin\Shared\PictureUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipesEditController extends AbstractController
{
    public function __construct(
        private readonly RecipeShowService $recipeShowService,
        private readonly PictureUploaderService $pictureUploaderService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/recipes/{id}/edit', name: 'app_admin_recipes_edit', methods: ['GET', 'POST'])]
    public function __invoke(string $id, Request $request): RedirectResponse|Response
    {
        try {
            $recipe = $this->recipeShowService->execute($id);
            $form = $this->createForm(RecipeFormType::class, $recipe);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $recipePicture = $form->get('picture')->getData();

                if ($recipePicture) {
                    $uploadsPath = $this->getParameter('uploads_folder') . '/recipes';
                    $pictureFilename = $this->pictureUploaderService->execute(
                        picture: $recipePicture,
                        uploadsPath: $uploadsPath,
                        filename: $recipe->getPicture()
                    );

                    $recipe->setPicture($pictureFilename);
                }

                $recipe->updateUpdatedAt();

                $this->entityManager->flush();

                $this->addFlash('success', 'Product updated');

                return $this->redirectToRoute('app_admin_recipes_index', compact('id'));
            }
        } catch (RecipeNotFoundException|InvalidPictureException|PictureNotUploadedException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_recipes_index');
        }

        return $this->render('admin/recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ]);
    }
}
