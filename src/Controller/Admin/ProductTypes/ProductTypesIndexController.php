<?php

declare(strict_types=1);

namespace App\Controller\Admin\ProductTypes;

use App\Service\Admin\ProductType\ProductTypeIndexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ProductTypesIndexController extends AbstractController
{
    #[Route('/product_types', name: 'app_admin_product_types_index', methods: ['GET'])]
    public function __invoke(ProductTypeIndexService $productTypeIndexService): Response
    {
        $productTypes = $productTypeIndexService->execute();

        return $this->render('admin/product_types/index.html.twig', compact('productTypes'));
    }
}