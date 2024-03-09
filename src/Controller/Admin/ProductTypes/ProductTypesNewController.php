<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductTypes;

use App\Entity\ProductType;
use App\Form\ProductTypeFormType;
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

class ProductTypesNewController extends AbstractController
{
    public function __construct(
        private readonly ActionButtonRendererService $actionButtonRendererService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/product_types/new', name: 'app_admin_product_types_new', methods: ['GET', 'POST'])]
    public function new(Request $request): RedirectResponse|Response
    {
        try {
            $productType = new ProductType();
            $form = $this->createForm(ProductTypeFormType::class, $productType, [
                'action' => $request->getRequestUri()
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($productType);
                $this->entityManager->flush();

                if ($request->isXmlHttpRequest()) {
                    $response = [
                        ucfirst($productType->getName()),
                        $this->actionButtonRendererService->execute([
                            $request->getRequestUri(),
                            $this->generateUrl('app_admin_product_types_delete', ['id' => $productType->getId()])
                        ], (string)$productType->getId())
                    ];

                    return $this->json($response, Response::HTTP_CREATED);
                }

                $this->addFlash('success', 'Product Type added');

                return $this->redirectToRoute('app_admin_recipe_types_index');
            }

            $template = $request->isXmlHttpRequest() ? '_form.html.twig' : 'new.html.twig';

            return $this->render('admin/recipe_types/' . $template, [
                'productType' => $productType,
                'form' => $form->createView()
            ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_CREATED));
        } catch (LoaderError|SyntaxError|RuntimeError $exception) {
            $exceptionMessage = $exception->getMessage();

            if ($request->isXmlHttpRequest()) {
                return new Response($exceptionMessage, Response::HTTP_BAD_REQUEST);
            }

            $this->addFlash('error', $exceptionMessage);

            return $this->redirectToRoute('app_admin_product_types_index');
        }
    }
}