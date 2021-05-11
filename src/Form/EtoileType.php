<?php

namespace App\Form;

use App\Entity\Etoile;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EtoileType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('retours', TextareaType::class, $this->getConfiguration("Feedbacks", "Qu'en dit mon environnement (managers, pairs..?"))
        ->add('comprehension', TextareaType::class, $this->getConfiguration("Compréhension de la mission Cible", "Qu'ai-je compris de la mission cible ? (enjeux, périmètres, responsabilités...)"))
        ->add('atouts', TextareaType::class, $this->getConfiguration("Mes atouts", "Quels sont mes atouts majeurs pour atteindre cette mission ?"))
        ->add('axes', TextareaType::class, $this->getConfiguration("Mes axes de développement", "Quels sont mes axes de développement ?"))
        ->add('firstActions', TextareaType::class, $this->getConfiguration("Mes actions entreprises", "Ai-je entrepris des actions qui contribuent à mon développement personnel (feedback, analyses de personnalité, autodiagnostic, journée de développement...) ? Si oui, qu'ai-je appris ?"))
        ->add('ratingEtoiles', CollectionType::class,
        [
            'entry_type' => RatingEtoileType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etoile::class,
        ]);
    }
}
