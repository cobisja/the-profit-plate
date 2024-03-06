<?php

declare(strict_types=1);

namespace App\Tests\Service\Admin\RecipeType;

use App\Entity\RecipeType;
use App\Exception\RecipeType\RecipeTypeNotFoundException;
use App\Repository\RecipeTypeRepository;
use App\Service\Admin\RecipeType\RecipeTypeShowService;
use PHPUnit\Framework\TestCase;

class RecipeTypeShowServiceTest extends TestCase
{
    private RecipeTypeRepository $recipeTypeRepository;

    protected function setUp(): void
    {
        $this->recipeTypeRepository = $this->createMock(RecipeTypeRepository::class);
    }

    /**
     * @test
     */
    public function it_should_triggers_a_recipe_type_not_found_exception(): void
    {
        $this->expectException(RecipeTypeNotFoundException::class);

        $recipeId = '123';

        $this->recipeTypeRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn(null);

        (new RecipeTypeShowService($this->recipeTypeRepository))->execute($recipeId);
    }

    /**
     * @test
     * @throws RecipeTypeNotFoundException
     */
    public function it_should_returns_the_recipe_type_requested(): void
    {
        $recipeId = '123';
        $expectedRecipeType = new RecipeType();

        $this->recipeTypeRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn($expectedRecipeType);

        $result = (new RecipeTypeShowService($this->recipeTypeRepository))->execute($recipeId);

        $this->assertEquals($expectedRecipeType, $result);
    }
}