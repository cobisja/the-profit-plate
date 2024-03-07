<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('delete-button')]
class DeleteButton
{
    final public const DEFAULT_HTTP_METHOD = 'post';

    public string $url;
    public ?string $httpMethod = self::DEFAULT_HTTP_METHOD;
    public string $csrfTokenId;

    public function setMethod(?string $method): void
    {
        if ($method) {
            $this->httpMethod = $method;
        }
    }
}