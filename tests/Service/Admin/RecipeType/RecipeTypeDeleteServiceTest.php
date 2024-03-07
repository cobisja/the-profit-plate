<?php

declare(strict_types=1);

namespace App\Tests\Service\Admin\RecipeType;

use App\Entity\RecipeType;
use App\Exception\RecipeType\RecipeTypeNotFoundException;
use App\Repository\RecipeTypeRepository;
use App\Service\Admin\RecipeType\RecipeTypeDeleteService;
use App\Service\Admin\RecipeType\RecipeTypeShowService;
use PHPUnit\Framework\TestCase;

class RecipeTypeDeleteServiceTest extends TestCase
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
    public function it_should_delete_the_requested_recipe_type(): void
    {
        $recipeId = '123';
        $recipeType = new RecipeType();

        $this->recipeTypeRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn($recipeType);

        $this->recipeTypeRepository
            ->expects(self::once())
            ->method('remove')
            ->with($recipeType);

        (new RecipeTypeDeleteService($this->recipeTypeRepository))->execute($recipeId);
    }
}