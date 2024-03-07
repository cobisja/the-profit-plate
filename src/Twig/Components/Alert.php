<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('alert')]
class Alert
{
    final public const SUCCESS_TYPE = 'success';
    final public const ERROR_TYPE = 'error';

    public ?string $type = null;
    public ?string $title = null;
    public string|array $message = '';
    public bool $dismissible = true;
    public string $class = 'primary';


    public function getIcon(): string
    {
        return match ($this->type) {
            self::SUCCESS_TYPE => 'ki-check-circle',
            self::ERROR_TYPE => 'ki-notification-bing',
            default => 'ki-information-2'
        };
    }

    public function getClassName(): string
    {
        return match ($this->type) {
            self::SUCCESS_TYPE => 'success',
            self::ERROR_TYPE => 'danger',
            default => 'primary'
        };
    }
}