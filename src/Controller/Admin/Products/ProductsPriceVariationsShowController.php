<?php

declare(strict_types=1);

namespace App\Controller\Admin\Products;

use App\Entity\ProductPriceVariation;
use App\Exception\Product\ProductNotFoundException;
use App\Service\Admin\Product\ProductShowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/admin')]
class ProductsPriceVariationsShowController extends AbstractController
{
    public function __construct(private readonly ProductShowService $productShowService)
    {
    }

    #[Route(
        path: '/products/{id}/price_variations/chart',
        name: 'app_admin_products_price_variations_chart_show',
        methods: ['POST'])
    ]
    public function index(string $id, ChartBuilderInterface $chartBuilder): Response
    {
        try {
            $product = $this->productShowService->execute($id);

            $priceVariations = $product->getPriceVariations();

            $labels = array_merge(
                ...array_map(
                    static function (ProductPriceVariation $priceVariation) {
                        $key = $priceVariation->getCreatedAt()->format('Y-m-d H:i');
                        $value = $priceVariation->getNewPrice();

                        return [$key => $value];
                    },
                    $priceVariations->toArray()
                )
            );

            $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

            $chart->setData([
                'labels' => array_reverse(array_keys($labels)),
                'datasets' => [
                    [
                        'label' => 'Price variations',
                        'backgroundColor' => 'rgb(255, 99, 132)',
                        'borderColor' => 'rgb(255, 99, 132)',
                        'data' => array_reverse(array_values($labels)),
                        'lineTension' => 0.2
                    ],
                ],
            ]);

            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                    ],
                ],
            ]);

            return $this->render('admin/products/show/_price-variations-chart-container.html.twig', [
                'product' => $product,
                'chart' => $chart,
            ]);
        } catch (ProductNotFoundException $exception) {
            return new Response($exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}