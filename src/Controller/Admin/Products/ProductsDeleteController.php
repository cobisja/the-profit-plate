<?php

declare(strict_types=1);

namespace App\Controller\Admin\Products;

use App\Exception\Product\ProductNotFoundException;
use App\Service\Admin\Product\ProductDeleteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ProductsDeleteController extends AbstractController
{
    public function __construct(private readonly ProductDeleteService $productDeleteService)
    {
    }

    #[Route('/products/{id}', name: 'app_admin_products_delete', methods: ['DELETE'])]
    public function __invoke(string $id, Request $request): RedirectResponse|Response
    {
        if (!$this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            return $this->sendResponse($request, Response::HTTP_BAD_REQUEST, 'error', 'Invalid csrf token!');
        }

        try {
            $this->productDeleteService->execute($id);

            $status = Response::HTTP_NO_CONTENT;
            $messageType = 'success';
            $messageText = 'The item was deleted';
        } catch (ProductNotFoundException $exception) {
            $status = Response::HTTP_BAD_REQUEST;
            $messageType = 'error';
            $messageText = $exception->getMessage();
        }

        return $this->sendResponse($request, $status, $messageType, $messageText);
    }

    private function sendResponse(
        Request $request,
        int $status,
        string $messageType,
        string $messageText
    ): RedirectResponse|Response {
        $this->addFlash($messageType, $messageText);

        if ($request->isXmlHttpRequest()) {
            return new Response($messageText, $status);
        }

        return $this->redirectToRoute('app_admin_products_index');
    }
}
