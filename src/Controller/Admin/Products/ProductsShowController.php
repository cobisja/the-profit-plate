<?php

declare(strict_types=1);

namespace App\Controller\Admin\Products;

use App\Exception\Product\ProductNotFoundException;
use App\Service\Admin\Product\ProductShowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ProductsShowController extends AbstractController
{
    public function __construct(private readonly ProductShowService $productShowService)
    {
    }

    #[Route('/products/{id}', 'app_admin_products_show', methods: ['GET'])]
    public function __invoke(string $id): RedirectResponse|Response
    {
        try {
            $product = $this->productShowService->execute($id);

            return $this->render('admin/products/show.html.twig', compact('product'));
        } catch (ProductNotFoundException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_admin_products_index');
        }
    }
}