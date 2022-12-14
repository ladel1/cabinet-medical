<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\AvailabilityRepository;
use App\Repository\RendezVousRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rendez-vous", name="app_rendezvous_")
 */
class RendezVousController extends AbstractController
{
    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function index(RendezVous $rendezvous): Response
    {
        return $this->render('rendez_vous/index.html.twig', compact("rendezvous"));
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(RendezVous $rendezvous,RendezVousRepository $repo): Response
    {
        $repo->remove($rendezvous,true);
        return $this->redirectToRoute("app_rendezvous_list");
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(Request $request,RendezVousRepository $rdvRepo): Response
    {

        $rendezVous = new RendezVous();
        $rendezVousForm = $this->createForm(RendezVousType::class,$rendezVous);
        if($request->isMethod("POST")){            
            $rdv = $request->request->get("rendez_vous");
            $rdv["dateDebut"] = new DateTime($rdv["dateDebut"]);
            $request->request->set("rendez_vous", $rdv);
        }
        $rendezVousForm->handleRequest($request);
        if($rendezVousForm->isSubmitted() ){
            $rendezVous->setDuree(30);
            $rendezVous->setPatient($this->getUser()->getPatient());
            $rdvRepo->add($rendezVous,true);
            return $this->redirectToRoute("app_rendezvous_add");
        }
        return $this->render('rendez_vous/add.html.twig', [  
            "rendezVousForm"=>$rendezVousForm->createView()         
        ]);
    }

    /**
     * @Route("/api/disponibilite/{date}/{medecin}",name="ajax")
    */
    public function ajax($date,$medecin,RendezVousRepository $rdvRepo,AvailabilityRepository $avaRepo):Response{
        $firstDayOfWeek = date_create("$date this week");//->format("Y-md H:i:s");
        $lastDayOfWeek = date_create("$date this week +6 days");
        $availabilities = $avaRepo->findAvailability($firstDayOfWeek,$lastDayOfWeek,$medecin);
        $rdvs = $rdvRepo->findByParams($medecin,array(
            "dateDebut"=>$firstDayOfWeek,
            "dateFin"=>$lastDayOfWeek
        ));

        return $this->render("rendez_vous/_table_disponibility.html.twig",[
            "selectedDay"=>$date,
            "firstDayOfWeek"=>$firstDayOfWeek,
            "availabilities"=>$availabilities,
            "rdvs"=>$rdvs
        ]);
    }



    /**
     * @Route("/list",name="list")
     */
    public function list(RendezVousRepository $repo):Response{
     
        return $this->render("rendez_vous/list.html.twig");
    }

}
