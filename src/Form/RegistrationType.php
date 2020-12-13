<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Rayon;
use App\Entity\Mission;
use App\Entity\Collaborateur;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matricule', NumberType::class, $this->getConfiguration("Matricule", "Matricule"))
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Nom"))
            ->add('prenom', TextType::class, $this->getConfiguration("Prénom", "Prénom"))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Email"))
            ->add('picture', UrlType::class, [
                'attr' => 
            [
                'placeholder' => 'URL de votre photo de profil'
            ],
                'required' => false,
                'empty_data' => 'http://cdn.onlinewebfonts.com/svg/img_24787.png',
            ])
            ->add('description', TextareaType::class, $this->getConfiguration("Présentation", "Présentez-vous en quelques mots"))
            ->add('hash', HiddenType::class, ['empty_data' => 'password@LM155'])
            ->add('statut', ChoiceType::class, [
            'choices'  => [
                'Employé' => 'Employé',
                'Agent de Maîtrise' => 'Agent de Maîtrise',
                'Cadre'=> 'Cadre',
            ],])
            ->add('mission', EntityType::class, [
                'class' => Mission::class,
                'choice_label' => 'title'
            ])
            ->add('rayon', EntityType::class, [
                'class'=>Rayon::class,
                'choice_label' => 'title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collaborateur::class,
        ]);
    }
}
