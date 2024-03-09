<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductTypes;

use App\Service\Admin\ProductType\ProductTypeIndexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ProductTypesIndexController extends AbstractController
{
    public function __construct(private readonly ProductTypeIndexService $productTypeIndexService)
    {
    }

    #[Route('/product_types', name: 'app_admin_product_types_index', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $productTypes = $this->productTypeIndexService->execute();

        if ($request->isXmlHttpRequest()) {
            return $this->render('admin/product_types/_product-types.html.twig', compact('productTypes'));
        }

        return $this->render('admin/product_types/index.html.twig', compact('productTypes'));
    }
}