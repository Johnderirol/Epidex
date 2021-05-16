<?php

namespace App\Form;

use App\Entity\CompLeader;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CompLeaderType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pole', TextType::class, $this->getConfiguration("Titre", "Titre"))
            ->add('definition', TextareaType::class, $this->getConfiguration("Définition", "Définition"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompLeader::class,
        ]);
    }
}
