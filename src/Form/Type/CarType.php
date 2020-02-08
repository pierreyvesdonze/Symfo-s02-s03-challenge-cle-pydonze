<?php

namespace App\Form\Type;

use App\Entity\Brand;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                "label" => "Marque de la voiture"
            ]
        );

        $builder->add(
            'brand',
            EntityType::class,
            [
                "class" => Brand::class,
                "choice_label" => "name"
            ]
        );

        $builder->add(
            'year',
            DateType::class,
            [
                "required" => true,
                "label"    => "Année de commercialisation",
                "years" => range(1900, date('Y'))
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
