<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductTypes;

use App\Exception\ProductType\ProductTypeNotFoundException;
use App\Form\ProductTypeFormType;
use App\Service\Admin\ProductType\ProductTypeShowService;
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
class ProductTypesEditController extends AbstractController
{
    public function __construct(
        private readonly ProductTypeShowService $productTypeShowService,
        private readonly ActionButtonRendererService $actionButtonRendererService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/product_types/{id}/edit', name: 'app_admin_product_types_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request): RedirectResponse|Response
    {
        try {
            $productType = $this->productTypeShowService->execute($id);
            $form = $this->createForm(ProductTypeFormType::class, $productType, [
                'action' => $request->getRequestUri()
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    $response = [
                        ucfirst($productType->getName()),
                        $this->actionButtonRendererService->execute([
                            $request->getRequestUri(),
                            $this->generateUrl('app_admin_recipe_types_delete', compact('id'))
                        ], (string)$productType->getId())
                    ];

                    return $this->json($response, Response::HTTP_OK);
                }

                $this->addFlash('success', 'Product Type updated');

                return $this->redirectToRoute('app_admin_product_types_index');
            }

            $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'edit.html.twig';

            return $this->render('admin/product_types/' . $template, [
                'productType' => $productType,
                'form' => $form->createView()
            ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
        } catch (ProductTypeNotFoundException|LoaderError|SyntaxError|RuntimeError $exception) {
            if ($request->isXmlHttpRequest()) {
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }

            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_product_types_index');
        }
    }
}