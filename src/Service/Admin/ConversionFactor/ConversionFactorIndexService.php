<?php

declare(strict_types=1);

namespace App\Service\Admin\ConversionFactor;

use App\Repository\ConversionFactorRepository;

readonly class ConversionFactorIndexService
{
    public function __construct(private ConversionFactorRepository $conversionFactorRepository)
    {
    }

    public function execute(): array
    {
        return $this->conversionFactorRepository->findBy(criteria: [], orderBy: [
            'originUnit' => 'ASC',
            'targetUnit' => 'ASC'
        ]);
    }
}
