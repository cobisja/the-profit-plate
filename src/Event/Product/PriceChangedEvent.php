<?php

declare(strict_types=1);

namespace App\Event\Product;

use App\Entity\Product;
use DateTimeImmutable;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Contracts\EventDispatcher\Event;

final class PriceChangedEvent extends Event
{
    public function __construct(
        private readonly Product $product,
        private readonly string $oldPrice,
        private readonly string $newPrice
    ) {
    }

    #[ArrayShape([
        'product' => "\App\Entity\Product",
        'old_price' => "string",
        'new_price' => "string",
        'createdAt' => "\DateTimeImmutable"
    ])] public function payload(): array
    {
        return [
            'product' => $this->product,
            'old_price' => $this->oldPrice,
            'new_price' => $this->newPrice,
            'createdAt' => new DateTimeImmutable()
        ];
    }
}
