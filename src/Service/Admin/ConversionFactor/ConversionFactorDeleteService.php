<?php

declare(strict_types=1);

namespace App\Service\Admin\ConversionFactor;

use App\Exception\ConversionFactor\ConversionFactorNotFoundException;
use App\Repository\ConversionFactorRepository;

readonly class ConversionFactorDeleteService
{
    public function __construct(private ConversionFactorRepository $conversionFactorRepository)
    {
    }

    /**
     * @throws ConversionFactorNotFoundException
     */
    public function execute(string $id): void
    {
        if (!$conversionFactor = $this->conversionFactorRepository->find($id)) {
            throw new ConversionFactorNotFoundException();
        }

        $this->conversionFactorRepository->remove($conversionFactor);
    }
}