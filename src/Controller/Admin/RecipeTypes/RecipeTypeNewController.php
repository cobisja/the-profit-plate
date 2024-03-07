<?php

declare(strict_types=1);

namespace App\Controller\Admin\RecipeTypes;

use App\Entity\RecipeType;
use App\Form\RecipeTypeFormType;
use App\Service\Admin\Shared\ActionButtonRendererService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipeTypeNewController extends AbstractController
{
    public function __construct(
        private readonly ActionButtonRendererService $actionButtonRendererService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/recipe_types/new', name: 'app_admin_recipe_types_new', methods: ['GET', 'POST'])]
    public function new(Request $request): RedirectResponse|Response
    {
        $recipeType = new RecipeType();
        $form = $this->createForm(RecipeTypeFormType::class, $recipeType, [
            'action' => $request->getRequestUri()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($recipeType);
            $this->entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                $response = [
                    ucfirst($recipeType->getName()),
                    number_format((float)$recipeType->getExpensesPercentage(), 2),
                    number_format((float)$recipeType->getProfitPercentage(), 2),
                    $this->actionButtonRendererService->execute([
                        $this->generateUrl('app_admin_recipe_types_edit', ['id' => $recipeType->getId()]),
                        $this->generateUrl('app_admin_recipe_types_delete', ['id' => $recipeType->getId()]),
                    ], (string)$recipeType->getId())
                ];

                return $this->json($response, Response::HTTP_CREATED);
            }

            return $this->redirectToRoute('app_admin_recipe_types_index');
        }

        $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

        return $this->render('admin/recipe_types/' . $template, [
            'recipeType' => $recipeType,
            'form' => $form->createView()
        ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_CREATED));
    }
}