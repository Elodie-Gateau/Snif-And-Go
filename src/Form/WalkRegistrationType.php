<?php

namespace App\Form;

use App\Entity\Dog;
use App\Entity\Walk;
use App\Entity\WalkRegistration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalkRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_registration')
            ->add('dog', EntityType::class, [
                'class' => Dog::class,
                'choice_label' => 'id',
            ])
            ->add('walk', EntityType::class, [
                'class' => Walk::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WalkRegistration::class,
        ]);
    }
}
