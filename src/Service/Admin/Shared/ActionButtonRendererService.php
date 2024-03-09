<?php

declare(strict_types=1);

namespace App\Service\Admin\Shared;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ActionButtonRendererService
{
    public function __construct(
        private readonly Environment $twig,
        private readonly CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function execute(array $routes, string $entityId): string
    {
        $buttons = $this->twig->render('components/edit-button.html.twig', [
            'url' => $routes[0]
        ]);

        $buttons .= $this->twig->render('components/delete-button.html.twig', [
            'url' => $routes[1],
            'csrfTokenId' => $this->csrfTokenManager->getToken('delete' . $entityId)->getValue(),
            'httpMethod' => 'delete'
        ]);

        return $buttons;
    }
}