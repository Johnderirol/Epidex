<?php

namespace App\Form;

use App\Entity\CompEtoile;
use App\Entity\RatingEtoile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class RatingEtoileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competences', EntityType::class, [
                'class' => CompEtoile::class,
                'choice_label' => 'title',
                'disabled'=> true,
                'help'=> 'description',
            ])
            ->add('note', RangeType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 4
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RatingEtoile::class,
        ]);
    }
}
