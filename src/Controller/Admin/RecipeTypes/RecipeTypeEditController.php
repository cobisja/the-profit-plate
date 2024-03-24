<?php

declare(strict_types=1);

namespace App\Controller\Admin\RecipeTypes;

use App\Exception\RecipeType\RecipeTypeNotFoundException;
use App\Form\RecipeTypeFormType;
use App\Service\Admin\RecipeType\RecipeTypeShowService;
use App\Service\Admin\Shared\ActionButtonRendererService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route('/admin')]
class RecipeTypeEditController extends AbstractController
{
    public function __construct(
        private readonly RecipeTypeShowService $recipeTypeShowService,
        private readonly ActionButtonRendererService $actionButtonRendererService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/recipe_types/{id}/edit', name: 'app_admin_recipe_types_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request): RedirectResponse|Response
    {
        try {
            $recipeType = $this->recipeTypeShowService->execute($id);
            $form = $this->createForm(RecipeTypeFormType::class, $recipeType, [
                'action' => $request->getRequestUri()
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    $response = [
                        ucfirst($recipeType->getName()),
                        number_format((float)$recipeType->getExpensesPercentage(), 2),
                        number_format((float)$recipeType->getProfitPercentage(), 2),
                        $this->actionButtonRendererService->execute([
                            $request->getRequestUri(),
                            $this->generateUrl('app_admin_recipe_types_delete', compact('id'))
                        ], (string)$recipeType->getId(), exclude: ActionButtonRendererService::SHOW_BUTTON)
                    ];

                    return $this->json($response);
                }

                $this->addFlash('success', 'Recipe Type updated');

                return $this->redirectToRoute('app_admin_recipe_types_index');
            }

            $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'edit.html.twig';

            return $this->render('admin/recipe_types/' . $template, [
                'recipeType' => $recipeType,
                'form' => $form->createView()
            ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
        } catch (RecipeTypeNotFoundException|LoaderError|SyntaxError|RuntimeError $exception) {
            if ($request->isXmlHttpRequest()) {
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }

            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_recipe_types_index');
        }
    }
}