<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Skill;
use App\Entity\Mission;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SkillType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description", "Description"))
            ->add('category', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'title',])
            ->add('missions', EntityType::class, [
                'class' => Mission::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    } 

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
