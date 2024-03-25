<?php

declare(strict_types=1);

namespace App\Controller\Admin\Products;

use App\Service\Admin\Product\ProductIndexService;
use App\Service\Admin\ProductType\ProductTypeIndexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ProductsIndexController extends AbstractController
{
    public function __construct(
        private readonly ProductIndexService $productIndexService,
        private readonly ProductTypeIndexService $productTypeIndexService
    ) {
    }

    #[Route('/products', name: 'app_admin_products_index', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $products = $this->productIndexService->execute();
        $productTypes = $this->productTypeIndexService->execute();

        return $this->render('admin/products/index.html.twig', compact('products', 'productTypes'));
    }
}