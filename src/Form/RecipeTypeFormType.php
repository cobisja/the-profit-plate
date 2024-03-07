<?php

namespace App\Form;

use App\Entity\RecipeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('expensesPercentage', NumberType::class, [
                'html5' => true,
                'input' => 'string',
                'scale' => 2
            ])
            ->add('profitPercentage', NumberType::class, [
                'html5' => true,
                'input' => 'string',
                'scale' => 2
            ])
            ->setAction($options['action']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeType::class,
            'action' => null
        ]);
    }
}
