<?php

declare(strict_types=1);

namespace App\Service\Admin\ConversionFactor;

use App\Entity\ConversionFactor;
use App\Exception\ConversionFactor\ConversionFactorNotFoundException;
use App\Repository\ConversionFactorRepository;

readonly class ConversionFactorShowService
{
    public function __construct(private ConversionFactorRepository $conversionFactorRepository)
    {
    }

    /**
     * @throws ConversionFactorNotFoundException
     */
    public function execute(string $id): ConversionFactor
    {
        if (!$conversionFactor = $this->conversionFactorRepository->find($id)) {
            throw new ConversionFactorNotFoundException();
        }

        return $conversionFactor;
    }
}
