<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {
   /**
    * Permet d'avoit la configuration de base du champ
    * @param string $label
    * @param string $placeholder
    * @param array $options
    * @return array
    */
    public function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => 
            [
                'placeholder' => $placeholder
            ]
        ],$options);        
    }
}


