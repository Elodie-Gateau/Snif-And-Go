<?php

namespace App\Form;

use App\Entity\Trail;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TrailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('distance')
            ->add('startAddress')
            ->add('startCode', null, [
                'attr' => ['id' => 'start-postal-code']
            ])
            ->add('startCity', TextType::class, [
                'attr' => ['id' => 'start-city'],
                'required' => true,
            ])
            ->add('endAddress')
            ->add('endCode', null, [
                'attr' => ['id' => 'end-postal-code']
            ])
            ->add('endCity', TextType::class, [
                'attr' => ['id' => 'end-city'],
                'required' => false,
            ])
            ->add('duration')
            ->add('difficulty')
            ->add('score')
            ->add('water_point')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trail::class,
            'required' => false
        ]);
    }
}
