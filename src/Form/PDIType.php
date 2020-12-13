<?php

namespace App\Form;

use App\Entity\PDI;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PDIType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('situationApprenante', TextareaType::class, $this->getConfiguration("Situations Apprenantes", "Situations choisies en vue d'un apprentissage"))
            ->add('progres', TextareaType::class, $this->getConfiguration("Progrès Attendus", "Liste des progrès attendus"))
            ->add('contributeursRoles', TextareaType::class, $this->getConfiguration("Les Contributeurs et leurs rôles", "Qui participera au développement du collaborateur et quels seront leurs rôles"))
            ->add('endDate', DateType::class, $this->getConfiguration("Date de fin", "Date d'évaluation des progrès", ["widget" => "single_text"]))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PDI::class,
        ]);
    }
}
