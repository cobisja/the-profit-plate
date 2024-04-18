<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ConversionFactor;
use App\Entity\Product;
use App\Entity\RecipeIngredient;
use App\Repository\ConversionFactorRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientFormType extends AbstractType
{
    public function __construct(private readonly ConversionFactorRepository $conversionFactorRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $availableUnits = $this->getAvailableUnits();

        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'label' => 'Ingredient',
                'placeholder' => 'Choose an ingredient...',
                'choice_label' => static function (Product $product) {
                    return ucfirst($product->getName());
                },
                'query_builder' => function (ProductRepository $productRepository): QueryBuilder {
                    return $productRepository
                        ->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                }
            ])
            ->add('quantity', NumberType::class, [
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                ]
            ])
            ->add('unit', ChoiceType::class, [
                'choices' => $availableUnits,
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            $form = $event->getForm();

            $fields = [
                'product' => $form->get('product')->getData(),
                'quantity' => $form->get('quantity')->getData(),
                'unit' => $form->get('unit')->getData(),
            ];

            foreach ($fields as $field => $value) {
                if (!$value) {
                    $form
                        ->get($field)
                        ->addError(
                            new FormError("The {$field} is required")
                        );
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class
        ]);
    }

    /**
     * @return string[]
     */
    public function getAvailableUnits(): array
    {
        $availableUnits = array_unique(
            array_merge(
                ...array_map(
                    fn(ConversionFactor $conversionFactor) => [
                        $conversionFactor->getOriginUnit(),
                        $conversionFactor->getTargetUnit()
                    ],
                    $this->conversionFactorRepository->findAll()
                )
            )
        );

        sort($availableUnits);

        return array_merge(...array_map(static fn(string $unit) => [$unit => $unit], $availableUnits));
    }
}
