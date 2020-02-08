<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Car;
use App\Form\Type\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/car")
 */
class CarController extends AbstractController
{

    /**
     * @Route("/list", name="cars_list")
     */
    public function carsList()
    {
        $carsRepository = $this->getDoctrine()->getRepository(Car::class);
        $cars = $carsRepository->findAll();

        $brandsRepository = $this->getDoctrine()->getRepository(Brand::class);
        $brands = $brandsRepository->findAll();

        dump($cars);


        return $this->render(
            'cars.html.twig',
            [
                'cars'   => $cars,
                'brands' => $brands
            ]
        );
    }

    /**
     * @Route("/create", name="car_create")
     */
    public function carCreate(Request $request)
    {
        $car = new Car;
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($car);
            $manager->flush();

            $this->addFlash("success", "La voiture a bien été ajoutée");
            return $this->redirectToRoute('cars_list');
        }

        return $this->render(
            "cars/car_add.html.twig",
            [
                "formView" => $form->createView()
            ]
        );
    }
}
