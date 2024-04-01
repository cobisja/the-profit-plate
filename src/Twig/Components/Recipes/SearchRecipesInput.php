<?php

declare(strict_types=1);

namespace App\Twig\Components\Recipes;

use App\Repository\RecipeRepository;
use App\Service\Admin\RecipeType\RecipeTypeIndexService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('search-recipes-input', template: 'components/recipes/search-recipes-input.html.twig')]
class SearchRecipesInput
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true, onUpdated: 'onQueryParamsUpdated', url: true)]
    public string $query = '';

    #[LiveProp(writable: true, onUpdated: 'onQueryParamsUpdated', url: true)]
    public string $recipeTypeName = '';

//    #[LiveProp(useSerializerForHydration: true)]
    public array $recipeTypes = [];

    public function __construct(
        private readonly RecipeRepository $recipeRepository,
        private readonly RecipeTypeIndexService $recipeTypeIndexService
    ) {
        $this->recipeTypes = $this->recipeTypeIndexService->execute();
    }

//    public function modifyRecipeTypes(LiveProp $prop): LiveProp
//    {
//        return $prop->withFormat($this->recipeTypes);
//    }

    public function getRecipes(): array
    {
        return $this->recipeRepository->searchByNameAndRecipeTypeName($this->query, $this->recipeTypeName);
    }

    public function onQueryParamsUpdated(): void
    {
        $this->dispatchBrowserEvent('recipes_search_updated');
    }
}
