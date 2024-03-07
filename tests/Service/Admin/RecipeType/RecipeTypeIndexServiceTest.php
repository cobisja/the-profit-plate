<?php

declare(strict_types=1);

namespace App\Tests\Service\Admin\RecipeType;

use App\Entity\RecipeType;
use App\Repository\RecipeTypeRepository;
use App\Service\Admin\RecipeType\RecipeTypeIndexService;
use PHPUnit\Framework\TestCase;

class RecipeTypeIndexServiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_returns_an_array_of_recipe_types(): void
    {
        $expectedResult = [new RecipeType()];

        $recipeTypeRepository = $this->createMock(RecipeTypeRepository::class);

        $recipeTypeRepository
            ->expects(self::once())
            ->method('findBy')
            ->willReturn($expectedResult);

        $result = (new RecipeTypeIndexService($recipeTypeRepository))->execute();

        $this->assertIsArray($result);
    }
}