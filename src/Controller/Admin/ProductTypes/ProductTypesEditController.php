<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductTypes;

use App\Exception\ProductType\ProductTypeNotFoundException;
use App\Form\ProductFormType;
use App\Service\Admin\ProductType\ProductTypeIndexService;
use App\Service\Admin\ProductType\ProductTypeShowService;
use App\Service\Admin\Shared\ActionButtonRendererService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductTypesEditController extends AbstractController
{
    public function __construct(
        private readonly ProductTypeIndexService $productTypeIndexService,
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
            $form = $this->createForm(ProductFormType::class, $productType, [
                'action' => $request->getRequestUri()
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                /**
                 * Non Ajax request => redirects to reflects changes
                 */
                if (!$request->isXmlHttpRequest()) {
                    $this->addFlash('success', 'Product Type updated');

                    return $this->redirectToRoute('app_admin_product_types_index');
                }

                /**
                 * Ajax request and not full-reload required => send updated row data
                 */
                if (!$request->headers->get('full-reload')) {
                    $response = [
                        ucfirst($productType->getName()),
                        $this->actionButtonRendererService->execute([
                            $request->getRequestUri(),
                            $this->generateUrl('app_admin_recipe_types_delete', compact('id'))
                        ], (string)$productType->getId())
                    ];

                    return $this->json($response);
                }

                /**
                 * Ajax request and full-reload required => renders the content of body table
                 */
                return $this->render('admin/product_types/_product-type.html.twig', [
                    'productTypes' => $this->productTypeIndexService->execute()
                ]);
            }

            $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'edit.html.twig';

            return $this->render('admin/product_types/' . $template, [
                'productType' => $productType,
                'form' => $form->createView()
            ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
        } catch (ProductTypeNotFoundException $exception) {
            if ($request->isXmlHttpRequest()) {
                return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }

            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_product_types_index');
        }
    }
}