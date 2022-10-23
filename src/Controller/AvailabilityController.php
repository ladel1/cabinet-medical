<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Form\AvailabilityType;
use App\Repository\AvailabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/availability")
 */
class AvailabilityController extends AbstractController
{
    /**
     * @Route("/", name="app_availability_index", methods={"GET"})
     */
    public function index(AvailabilityRepository $availabilityRepository): Response
    {
        return $this->render('availability/index.html.twig', [
            'availabilities' => $availabilityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter", name="app_availability_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AvailabilityRepository $availabilityRepository): Response
    {
        $availability = new Availability();
        $form = $this->createForm(AvailabilityType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $availabilityRepository->add($availability, true);

            return $this->redirectToRoute('app_availability_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('availability/new.html.twig', [
            'availability' => $availability,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_availability_show", methods={"GET"})
     */
    public function show(Availability $availability): Response
    {
        return $this->render('availability/show.html.twig', [
            'availability' => $availability,
        ]);
    }

  

    /**
     * @Route("/{id}/modifier", name="app_availability_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Availability $availability, AvailabilityRepository $availabilityRepository): Response
    {
        $form = $this->createForm(AvailabilityType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $availabilityRepository->add($availability, true);

            return $this->redirectToRoute('app_availability_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('availability/edit.html.twig', [
            'availability' => $availability,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_availability_delete", methods={"POST"})
     */
    public function delete(Request $request, Availability $availability, AvailabilityRepository $availabilityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$availability->getId(), $request->request->get('_token'))) {
            $availabilityRepository->remove($availability, true);
        }

        return $this->redirectToRoute('app_availability_index', [], Response::HTTP_SEE_OTHER);
    }




}
