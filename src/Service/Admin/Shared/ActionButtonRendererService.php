<?php

declare(strict_types=1);

namespace App\Service\Admin\Shared;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

class ActionButtonRendererService
{
    public function __construct(
        private readonly Environment $twig,
        private readonly CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    public function execute(array $routes, string $entityId): string
    {
        $buttons = sprintf(
            <<<HTML
            <a href="%s"
               class="btn btn-sm btn-light-primary ms-lg-5 float-start"
               data-action="admin--shared--modal-form#openModal">Edit</a>
            HTML,
            $routes[0]
        );

        $buttons .= $this->twig->render('components/delete-button.html.twig', [
            'url' => $routes[1],
            'csrfTokenId' => $this->csrfTokenManager->getToken('delete' . $entityId)->getValue(),
            'httpMethod' => 'delete'
        ]);

        return $buttons;
    }
}