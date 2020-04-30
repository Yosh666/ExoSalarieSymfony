<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route ("/entreprises")
 */
class EntrepriseController extends AbstractController
{

    /***
     * @Route("/add", name="entreprise_add")
     * @Route("/{id}/edit", name="entreprise_edit" )
     */
    public function addEntreprise(Entreprise $entreprise,Request $request,EntityManagerInterface $manager){
        if(!$entreprise){
            $entreprise= new Entreprise();
        }
        $form=$this->createForm(EntrepriseType::class,$entreprise);


        return $this->render(
            "entreprise/add_edit.html.twig",
            [
            'form'=>$form->createView(),
            'editMode'=>$entreprise->getId()!== null
            ]);
    }


    /**
     * @Route("/", name="entreprises_index")
     */
    public function index()
    {
        $entreprises=$this->getDoctrine()
            ->getRepository(Entreprise::class)
            ->getAll();

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    /**
     * @Route("/{id}",name="entreprise_show",methods="GET")
     */
    public function show(Entreprise $entreprise):Response{
        return $this->render('entreprise/show.html.twig',['entreprise'=>$entreprise]);
    }
}
