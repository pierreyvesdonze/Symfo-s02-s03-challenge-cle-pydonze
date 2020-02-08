<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CarType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'model',
            TextType::class,
            [
                "label" => "Modèle de la voiture"
            ]
        );

        $builder->add(
            'year',
            DateType::class,
            [
                "required" => true,
                "label"    => "Année de commercialisation",
                "years" => range(1900, date('Y')+5)
            ]
        );

        $builder->add(
            'save',
            SubmitType::class, 
            [
                "label" => "Ajouter"
            ]
        );
    }
}
