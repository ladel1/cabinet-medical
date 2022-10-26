<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil")
     */
    public function index(Request $request,ProfilRepository $repo): Response
    {
        if($this->isGranted("ROLE_USER")){
            $user = $this->getUser();
            $profilForm= $this->createForm(ProfilType::class,$user->getProfil());
            $profilForm->handleRequest($request);
            if($profilForm->isSubmitted() && $profilForm->isValid()){
                $repo->update();
                return $this->redirectToRoute("app_profil");
            }
            return $this->render('profil/index.html.twig', [
                'profilForm'=>$profilForm->createView()
            ]);
        }else{
            return $this->redirectToRoute("app_login");
        }
    }
}
