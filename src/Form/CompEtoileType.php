<?php

namespace App\Form;

use App\Entity\CompEtoile;
use App\Entity\MissionCible;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompEtoileType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description", "Description"))
            ->add('def', TextareaType::class, $this->getConfiguration("Définition", "Définition"))
            ->add('missionCibles', EntityType::class, [
                'class' => MissionCible::class,
                'choice_label' => 'title',
                'by_reference'=> false,
                'multiple' => true,
                'expanded' => true
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompEtoile::class,
        ]);
    }
}
