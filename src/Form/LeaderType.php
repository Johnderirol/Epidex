<?php

namespace App\Form;

use App\Entity\Leader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ratingLeaders', CollectionType::class,
            [
                'entry_type' => RatingLeaderType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Leader::class,
        ]);
    }
}
