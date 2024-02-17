<?php

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    final public const LOGOUT_ROUTE = 'app_logout';
    final public const BAD_CREDENTIALS_TRANSLATION_KEY = 'user.domain.exception.bad_credentials';

    public function __construct(private readonly RouterInterface $router)
    {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse
    {
        $request->getSession()->getFlashBag()->add('error', self::BAD_CREDENTIALS_TRANSLATION_KEY);

        return new RedirectResponse(
            $this->router->generate(self::LOGOUT_ROUTE)
        );
    }
}