<?php

namespace App\Form;

use App\Entity\Etoile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EtoileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ratingEtoiles', CollectionType::class,
        [
            'entry_type' => RatingEtoileType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etoile::class,
        ]);
    }
}
