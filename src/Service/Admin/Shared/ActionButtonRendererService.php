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
    final public const SHOW_BUTTON = 'show';
    final public const EDIT_BUTTON = 'edit';
    final public const DELETE_BUTTON = 'delete';

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
    public function execute(array $routes, string $entityId, string|array $exclude = []): string
    {
        $buttons = '';

        $buttonsToRender = array_diff(
            [self::SHOW_BUTTON, self::EDIT_BUTTON, self::DELETE_BUTTON],
            is_string($exclude) ? [$exclude] : $exclude
        );

        in_array(self::SHOW_BUTTON, $buttonsToRender) && ($buttons .= $this->twig->render(
            'components/edit-button.html.twig',
            ['url' => $routes[0]]
        ));

        in_array(self::EDIT_BUTTON, $buttonsToRender) && ($buttons .= $this->twig->render(
            'components/edit-button.html.twig',
            ['url' => $routes[0]]
        ));

        in_array(self::DELETE_BUTTON, $buttonsToRender) && ($buttons .= $this->twig->render(
            'components/delete-button.html.twig',
            [
                'url' => $routes[1],
                'csrfTokenId' => $this->csrfTokenManager->getToken('delete' . $entityId)->getValue(),
                'httpMethod' => 'delete'
            ]
        ));

        return $buttons;
    }
}