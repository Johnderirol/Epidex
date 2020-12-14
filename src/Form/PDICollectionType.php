<?php

namespace App\Form;

use App\Entity\PDI;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PDICollectionType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('PDI', CollectionType::class,
        [
            'entry_type' => PDIType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PDI::class,
        ]);
    }
}
