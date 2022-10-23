<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\AvailabilityRepository;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rendez-vous")
 */
class RendezVousController extends AbstractController
{

    /**
     * @Route("/", name="app_rendez_vous_index", methods={"GET"})
     */
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }

    /**
     * @Route("/get/{date}/{medecin}", name="app_availability_get", methods={"GET"})
     */
    public function getAvailabilities(AvailabilityRepository $repo,RendezVousRepository $rendezVousRepository,$date,$medecin): Response{

        $First_date = date_create("$date this week")->format('Y-m-d H:i:s');
        $Last_date = date_create("$date this week +5 days")->format('Y-m-d H:i:s');
        $rdvs = $rendezVousRepository->findByCostum($First_date,$Last_date,$medecin);
        $ava = $repo->findAvailability($First_date,$Last_date,$medecin);
        return $this->render('rendez_vous/_table_availability.html.twig', [
            'selectedDay' => $date,
            'availabilities'=>$ava,
            "rdvs"=>$rdvs
        ]);
    }      

    /**
     * @Route("/ajouter", name="app_rendez_vous_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RendezVousRepository $rendezVousRepository): Response
    {
        $rendezVou = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->add($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_rendez_vous_show", methods={"GET"})
     */
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }

    /**
     * @Route("/{id}/modifier", name="app_rendez_vous_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->add($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_rendez_vous_delete", methods={"POST"})
     */
    public function delete(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            $rendezVousRepository->remove($rendezVou, true);
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
}
