<?php

namespace App\Form;

use App\Entity\ConversionFactor;
use App\Entity\Plus\Plus;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Repository\ConversionFactorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProductFormType extends AbstractType
{
    public function __construct(private readonly ConversionFactorRepository $conversionFactorRepository)
    {
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['picture_url'] = $options['picture_url'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
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

        $builder
            ->add('name')
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg'
                        ],
                        'minWidth' => 300,
                        'minHeight' => 300,
                        'maxRatio' => 1.15,
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
            ->add('unit', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'choices' => array_merge(...array_map(static fn(string $unit) => [$unit => $unit], $availableUnits))
            ])
            ->add('pricePerUnit')
            ->add('notes', HiddenType::class)
            ->add('productType', EntityType::class, [
                'class' => ProductType::class,
                'placeholder' => 'Choose an option',
                'choice_label' => static fn(ProductType $productType) => ucwords($productType->getName()),
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use (
            $options,
            $availableUnits
        ) {
            $form = $event->getForm();

            $currentPicture = $options['data']->getPicture();
            $uploadedPicture = $form->get('picture')->getData();

            if (empty($currentPicture) && !$uploadedPicture) {
                $form
                    ->get('picture')
                    ->addError(
                        new FormError('The product picture is required')
                    );
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'picture_url' => null
        ]);
    }
}
