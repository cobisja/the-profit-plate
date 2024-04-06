<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Recipe;
use App\Entity\RecipeType;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class RecipeFormType extends AbstractType
{
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['picture_url'] = $options['picture_url'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('name')
            ->add('description')
            ->add('directions')
            ->add('expensesPercentage', NumberType::class, [
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                ]
            ])
            ->add('profitPercentage', NumberType::class, [
                'html5' => true,
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                ]
            ])
            ->add('recipeType', EntityType::class, [
                'class' => RecipeType::class,
                'label' => 'Type',
                'placeholder' => 'Choose an option',
                'choice_label' => static fn(RecipeType $recipeType) => ucwords($recipeType->getName()),
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => RecipeIngredientFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);


        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            $form = $event->getForm();

            $currentPicture = $options['data']->getPicture();
            $uploadedPicture = $form->get('picture')->getData();

            if (empty($currentPicture) && !$uploadedPicture) {
                $form
                    ->get('picture')
                    ->addError(
                        new FormError('The recipe picture is required')
                    );
            }

            $recipeType = $form->get('recipeType')->getData();

            if (!$recipeType) {
                $form
                    ->get('recipeType')
                    ->addError(
                        new FormError('The recipe type is required')
                    );
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'picture_url' => null
        ]);
    }

    public function getAvailableProducts(): array
    {
        return array_merge(
            ...array_map(
                static fn(Product $product) => [ucfirst($product->getName()) => (string)$product->getId()],
                $this->productRepository->findBy([], ['name' => 'ASC'])
            )
        );
    }
}
