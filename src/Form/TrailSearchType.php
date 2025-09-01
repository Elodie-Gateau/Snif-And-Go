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
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'attr' => [
                    'placeholder' => 'Saisir une ville ou un nom d\'itinéraire',
                    'class' => 'home-user__trail--search-input'
                ],
            ])

            ->add('difficulty', ChoiceType::class, [
                'label' => 'Niveau de difficulté',
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'placeholder' => 'Toutes difficultés',
                'attr' => ['class' => 'home-user__trail--search-input'],
                'choices' => [
                    'Facile' => 'easy',
                    'Moyen' => 'medium',
                    'Difficile' => 'hard',
                ],
            ])
            ->add('minDistance', IntegerType::class, [
                'label' => 'Distance minimum (en km)',
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'attr' => ['class' => 'home-user__trail--search-input']
            ])
            ->add('maxDistance', IntegerType::class, [
                'label' => 'Distance maximum (en km)',
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'attr' => ['class' => 'home-user__trail--search-input']
            ])
            ->add('minDuration', IntegerType::class, [
                'label' => 'Durée de la balade minimale (en minutes)',
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'attr' => ['class' => 'home-user__trail--search-input']
            ])
            ->add('maxDuration', IntegerType::class, [
                'label' => 'Durée de la balade maximale (en minutes)',
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'attr' => ['class' => 'home-user__trail--search-input']
            ])
            ->add('minScore', IntegerType::class, [
                'label' => 'Score minimum',
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'attr' => ['class' => 'home-user__trail--search-input']
            ])
            ->add('maxScore', IntegerType::class, [
                'label' => 'Score maximum',
                'label_attr' => ['class' => 'home-user__trail--search-label'],
                'attr' => ['class' => 'home-user__trail--search-input']
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
