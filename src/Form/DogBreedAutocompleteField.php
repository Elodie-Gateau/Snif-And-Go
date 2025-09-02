<?php

namespace App\Form;

use App\Entity\DogBreed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class DogBreedAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => DogBreed::class,
            'placeholder' => 'Choisissez une race',
            'choice_label' => 'name_fr',
            'searchable_fields' => ['name_fr'],
            'attr' => ['class' => 'add-dog__form-input-autocomplete'],
            'label' => 'Race du chien :',
            'label_attr' => ['class' => 'add-dog__form-label'],
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
