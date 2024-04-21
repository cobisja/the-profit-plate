<?php

declare(strict_types=1);

namespace App\Controller\Admin\Dashboard;

use App\Service\Admin\Dashboard\DashboardStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardIndexController extends AbstractController
{
    public function __construct(private readonly DashboardStatsService $dashboardStatsService)
    {
    }

    #[Route('/dashboard', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(): Response
    {
        $stats = $this->dashboardStatsService->execute();

        return $this->render('admin/dashboard/index.html.twig', compact('stats'));
    }
}
