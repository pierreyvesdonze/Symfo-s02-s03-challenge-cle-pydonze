<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\Type\brandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/brand")
 */
class BrandController extends AbstractController
{

    /**
     * @Route("/list", name="brands_list")
     */
    public function brandsList()
    {
    
        $brandsRepository = $this->getDoctrine()->getRepository(Brand::class);
        $brands = $brandsRepository->findAll();

        return $this->render(
            'brands/brands.html.twig',
            [
                'brands'   => $brands,
            ]
        );
    }

     /**
     * @Route("/create", name="brand_create")
     */
    public function brandCreate(Request $request)
    {
        $brand = new Brand;
        $form = $this->createForm(brandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($brand);
            $manager->flush();

            $this->addFlash("success", "La marque a bien été ajoutée");

            return $this->redirectToRoute('brands_list');
        }

        return $this->render(
            "brands/brand_add.html.twig",
            [
                "formView" => $form->createView()
            ]
        );
    }

}
