<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class TrailSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Saisie une ville ou un nom d\'itinéraire'],
            ])

            ->add('difficulty', ChoiceType::class, [
                'placeholder' => 'Toutes difficultés',
                'choices' => [
                    'Facile' => 'easy',
                    'Moyen' => 'medium',
                    'Difficile' => 'hard',
                ],
            ])
            ->add('minDistance', IntegerType::class, [
                'label' => 'Distance min (km)',
            ])
            ->add('maxDistance', IntegerType::class, [
                'label' => 'Distance max (km)',
            ])
            ->add('minDuration', IntegerType::class, [
                'label' => 'Durée min (min)',
            ])
            ->add('maxDuration', IntegerType::class, [
                'label' => 'Durée max (min)',
            ])
            ->add('minScore', IntegerType::class, [
                'label' => 'Score min',
            ])
            ->add('maxScore', IntegerType::class, [
                'label' => 'Score max',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'required' => false,

        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
