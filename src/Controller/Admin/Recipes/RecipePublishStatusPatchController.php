<?php

declare(strict_types=1);

namespace App\Controller\Admin\Recipes;

use App\Exception\Recipe\RecipeNotFoundException;
use App\Service\Admin\Recipes\RecipePublishStatusUpdaterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class RecipePublishStatusPatchController extends AbstractController
{
    public function __construct(private readonly RecipePublishStatusUpdaterService $publishStatusUpdaterService)
    {
    }

    #[Route(
        path: '/recipes/{id}/publish',
        name: 'app_admin_recipes_publish_status_patch',
        methods: ['PATCH']
    )]
    public function __invoke(string $id, Request $request): JsonResponse|Response
    {
        if (!$this->isCsrfTokenValid('publish' . $id, $request->request->get('_csrf_token'))) {
            return new Response(status: Response::HTTP_UNAUTHORIZED);
        }

        try {
            $this->publishStatusUpdaterService->execute($id, $request->request->getBoolean('published_status'));

            return new Response(status: Response::HTTP_NO_CONTENT);
        } catch (RecipeNotFoundException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
