<?php

declare(strict_types=1);

namespace App\Twig\Components\Recipes;

use App\Entity\Recipe;
use App\Form\RecipeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'ingredients-collection', template: 'components/recipes/ingredients-collection.html.twig')]
class IngredientsCollection extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public Recipe $initialFormData;

    #[LiveAction]
    public function addIngredientRow(): void
    {
        $this->formValues['ingredients'][] = [];
    }

    #[LiveAction]
    public function removeIngredientRow(#[LiveArg] int $index): void
    {
        unset($this->formValues['ingredients'][$index]);
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(RecipeFormType::class, $this->initialFormData);
    }
}
