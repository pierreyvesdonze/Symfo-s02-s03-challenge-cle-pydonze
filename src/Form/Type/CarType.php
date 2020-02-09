<?php

namespace App\Form\Type;

use App\Entity\Brand;
use App\Entity\Car;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'brand',
            EntityType::class,
            [
                "class" => Brand::class,
                "choice_label" => "name"
            ]
        );

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
                "years" => range(1900, date('Y'))
            ]
        );


        $builder->add('brochure', FileType::class, [
          
            'label'         => 'Ajouter une image (JPG)',
            'mapped'        => false,
            'required'      => true,
            'constraints'   => [
                new File()
            ],
        ]);

        
        $builder->add(
            'save',
            SubmitType::class, 
            [
                "label" => "Ajouter"
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
