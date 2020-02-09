<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Car;
use App\Form\Type\CarType;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        /** @var CarRepository */
        $carsRepository = $this->getDoctrine()->getRepository(Car::class);
        $cars = $carsRepository->findAll();

        return $this->render(
            'cars/cars.html.twig',
            [
                'cars'   => $cars,
            ]
        );
    }

     /**
     * @Route("/brand/{id}/list", name="brand_cars_list")
     */
    public function carsListByBrand(Brand $brand)
    {
        /** @var CarRepository */
        $carsRepository = $this->getDoctrine()->getRepository(Car::class);
        $cars = $carsRepository->findBy(
            ['brand' => $brand]
        );

        return $this->render(
            'cars/cars_by_brand.html.twig',
            [
                'cars'   => $cars,
                'brand'  => $brand
            ]
        );
    }

       /**
     * @Route("/view/{id}", name="car_view")
     */
    public function carView(Car $car) {

        return $this->render('cars/view.html.twig', [
            'car' => $car
        ]);
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

            $brochureFile = $form->get('brochure')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                    echo("L'image n'a pas été chargée");
                }


                $car->setBrochureFilename($newFilename);
            }

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

     /**
     * @Route("/{id}/update", name="car_update")
     */
    public function carUpdate(Request $request, Car $car)
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash("success", "La voiture a bien été modifiée");

            return $this->redirectToRoute('cars_list', ['id' => $car->getId()]);
        }

        return $this->render(
            "cars/update.html.twig",
            [
                "formView" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="car_delete")
     */
    public function carDelete(Car $car)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($car);
        $manager->flush();

        $this->addFlash("success", "La voiture a bien été supprimée");

        return $this->redirectToRoute('homepage');
    }
}
