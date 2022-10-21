<?php

namespace App\Controller;

use App\Entity\Medecin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedAvailabilityController extends AbstractController
{
    /**
     * @Route("/med/availability", name="app_med_availability")
     */
    public function index(): Response
    {
        return $this->render('med_availability/index.html.twig', [
            'controller_name' => 'MedAvailabilityController',
        ]);
    }


    /**
     * @Route("/medecin/dispo/{id}", name="app_med_availability")
     */
    public function add(Medecin $medecin,Request $request):Response{
        return $this->render('med_availability/add.html.twig',compact('medecin'));
    }
}
