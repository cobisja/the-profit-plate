<?php

namespace App\Form;

use App\Entity\ConversionFactor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversionFactorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('originUnit')
            ->add('targetUnit')
            ->add('factor', NumberType::class, [
                'html5' => true,
                'input' => 'string'
            ])
            ->setAction($options['action']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConversionFactor::class,
            'action' => null
        ]);
    }
}
