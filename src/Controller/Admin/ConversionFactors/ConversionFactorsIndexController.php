<?php

declare(strict_types=1);

namespace App\Controller\Admin\ConversionFactors;

use App\Service\Admin\ConversionFactor\ConversionFactorIndexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin')]
class ConversionFactorsIndexController extends AbstractController
{
    public function __construct(private readonly ConversionFactorIndexService $conversionFactorIndexService)
    {
    }

    #[Route('/conversion_factors', name: 'app_admin_conversion_factors_index', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $conversionFactors = $this->conversionFactorIndexService->execute();

        return $this->render('admin/conversion_factors/index.html.twig', compact('conversionFactors'));
    }
}
