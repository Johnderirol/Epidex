<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Skill;
use App\Entity\Rating;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceValue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;

class RatingType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('competences', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'title',
                'group_by' => function (Skill $skill) {
                    return $skill->getCategory()->getTitle();
                }
            ])
            ->add('note', RangeType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 4,
                    'step' => 1,
                    'value' => 2
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}
