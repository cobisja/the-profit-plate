<?php

namespace App\Controller\Frontend\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuIndexController extends AbstractController
{
    #[Route('/menu', name: 'app_frontend_menu')]
    public function index(): Response
    {
        return $this->render('frontend/menu/index.html.twig');
    }
}