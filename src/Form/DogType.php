<?php

namespace App\Form;

use App\Entity\Dog;
use App\Entity\DogBreed;
use App\Entity\User;
use App\Repository\DogBreedRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $allowedSizes = $options['allowed_sizes'];

        $sizeLabels = [
            'small'  => 'Petit gabarit',
            'medium' => 'Moyen gabarit',
            'large'  => 'Grand gabarit',
            'giant'  => 'Très grand gabarit',
        ];

        $builder
            ->add('name', null, [
                'attr' => ['class' => 'add-dog__form-input'],
                'label' => 'Nom du chien :',
                'label_attr' => ['class' => 'add-dog__form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez saisir un nom",
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('birth_date', DateType::class, [
                'widget' => 'choice',
                'years' => range(2005, 2030),
                'attr' => ['class' => 'add-dog__form-input'],
                'label' => 'Date de naissance :',
                'label_attr' => ['class' => 'add-dog__form-label'],
            ])
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'Choisir le genre' => null,
                    'Femelle' => 'Female',
                    'Mâle' => 'Male'
                ],
                'attr' => ['class' => 'add-dog__form-input'],
                'label' => 'Genre :',
                'label_attr' => ['class' => 'add-dog__form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez choisir un genre",
                    ])
                ],
            ])
            ->add('dogBreed', EntityType::class, [
                'class' => DogBreed::class,
                'query_builder' => function (DogBreedRepository $r) {
                    return $r->createQueryBuilder('b')
                        ->orderBy('b.name_fr', 'ASC');
                },
                'choice_label' => 'name_fr',
                'group_by' => function (DogBreed $breed) use ($sizeLabels) {
                    return $sizeLabels[$breed->getSize()] ?? ucfirst($breed->getSize());
                },

                // 'choice_filter' => function ($breed) use ($allowedSizes) {
                //     if (!$breed instanceof DogBreed) {
                //         return true; // placeholder / valeurs techniques : on ne filtre pas
                //     }
                //     if (empty($allowedSizes)) {
                //         return true; // pas de filtre demandé
                //     }
                //     return in_array($breed->getSize(), $allowedSizes, true);
                // },

                'attr' => ['class' => 'add-dog__form-input'],
                'label' => 'Race du chien :',
                'label_attr' => ['class' => 'add-dog__form-label'],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez choisir une race",
                    ])
                ],
            ])
            ->add(
                'identity_number',
                null,
                [
                    'attr' => ['class' => 'add-dog__form-input'],
                    'label' => 'Nom du chien :',
                    'label_attr' => ['class' => 'add-dog__form-label'],
                ],
            )

            ->add('photo', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5000k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Image trop lourde',
                    ])
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dog::class,
            // 'allowed_sizes' => [],
        ]);
        // $resolver->setAllowedTypes('allowed_sizes', 'array');
    }
}
