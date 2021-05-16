<?php

namespace App\Form;

use App\Entity\CompLeader;
use App\Entity\RatingLeader;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class RatingLeaderType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pole', EntityType::class, [
                'class' => CompLeader::class,
                'choice_label' => 'pole',
                'disabled'=> true,
                'help'=> 'definition',
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
            'data_class' => RatingLeader::class,
        ]);
    }
}
