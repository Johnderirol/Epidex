<?php

namespace App\Form;
 
use App\Entity\Rayon;
use App\Entity\Secteur;
use App\Entity\Collaborateur;
use App\Form\ApplicationType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\CollaborateurRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SecteurType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre"))
            ->add('responsable', EntityType::class, [
                'class' => Collaborateur::class,
                'choice_label' => 'fullName',
                'query_builder' => function (CollaborateurRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->andWhere('c.statut = :val')
                    ->setParameter('val', "Cadre")
                    ->orderBy('c.nom', 'ASC');
                }]);
    }


    
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Secteur::class,
        ]);
    }
}
