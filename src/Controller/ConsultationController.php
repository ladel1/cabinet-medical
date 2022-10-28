<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\RendezVous;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/consultation")
 */
class ConsultationController extends AbstractController
{
    /**
     * @Route("/", name="app_consultation_index", methods={"GET"})
     */
    public function index(ConsultationRepository $consultationRepository): Response
    {
        $consultation = [];
        if($this->getUser()->getMedecin()==null){// patient
            $consultation=$consultationRepository->myConsultations($this->getUser()->getPatient(),"patient");
        }else{ // medecin
            $consultation=$consultationRepository->myConsultations($this->getUser()->getMedecin(),"medecin");
        }

        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultation,
        ]);
    }

    /**
     * @Route("/ajouter/{id}", name="app_consultation_new", methods={"GET", "POST"})
     */
    public function new(Request $request,RendezVous $rdv, ConsultationRepository $consultationRepository): Response
    {
        $consultation = new Consultation();

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setRendezVous($rdv);
            $consultationRepository->add($consultation, true);

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_consultation_show", methods={"GET"})
     */
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_consultation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultationRepository->add($consultation, true);

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_consultation_delete", methods={"POST"})
     */
    public function delete(Request $request, Consultation $consultation, ConsultationRepository $consultationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $consultationRepository->remove($consultation, true);
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }
}
