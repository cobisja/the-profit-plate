<?php

namespace App\EventSubscriber;

use App\Entity\ProductPriceVariation;
use App\Event\Product\PriceChangedEvent;
use App\Repository\ProductPriceVariationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PriceChangedSubscriber implements EventSubscriberInterface
{
    public function __construct(private ProductPriceVariationRepository $productPriceVariationRepository)
    {
    }

    public function onPriceChangedEvent(PriceChangedEvent $event): void
    {
        $eventPayload = $event->payload();
        $priceVariation = new ProductPriceVariation();

        $priceVariation->setProduct($eventPayload['product']);
        $priceVariation->setOldPrice($eventPayload['old_price']);
        $priceVariation->setNewPrice($eventPayload['new_price']);

        $this->productPriceVariationRepository->save($priceVariation);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PriceChangedEvent::class => 'onPriceChangedEvent',
        ];
    }
}
