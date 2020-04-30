<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Salarie;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/***
 * @Route("/salaries")
 */
class SalarieController extends AbstractController
{
    /**
     * @Route ("/edit/{id}", name="salarie_edit", methods="GET")
     * @Route("/add",name="salarie_add",methods="GET")
     */
    public function addSalarie(Salarie $salarie = null,Request $request){
        
        if(!$salarie){
            $salarie= new Salarie();
        }

        $form= $this->createFormBuilder($salarie)

            ->add('nom',TextType::class)
            ->add('prenom', TextType ::class)
            ->add('datenaissance',DateType::class,[
                'years'=>range(date('Y'),date('Y')-70),
//the Fuck -70??
                'label'=> 'Date de naissance',
                'format' => 'ddMMMMyyyy'
            ])
            
            ->add('adresse',TextType::class)
            ->add('cp',TextType::class)
            ->add('ville',TextType::class)
            ->add('dateEmbauche',DateType::class,[
                'years'=>range(date('Y'),date('Y')-70),
                //the Fuck -70??
                'label'=> 'Date d\'embauche',
                'format' => 'ddMMMMyyyy'
            ])
            ->add('Entreprise',EntityType::class,[
                'class'=> Entreprise::class,
                'choice_label'=> 'raisonSociale',
            ])
            ->add('Valider',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $manager->persist($salarie);
            $manager->flush();

            return $this->redirectToRoute('salaries_index');

        }

        return $this->render ('salarie/add_edit.html.twig',[
            'form'=>$form->createView(),
            'editMode' => $salarie->getId() !== null
        ]);

    }



    /**
     * @Route("/", name="salaries_index")
     */
    public function index()
    {   $salaries=$this->getDoctrine()
            ->getRepository(Salarie::class)
            ->getAll();

        return $this->render('salarie/index.html.twig', [
            'salaries' => $salaries,
        ]);
    }

    /**
     * @Route("/{id}",name="salarie_show",methods="GET")
     */
    public function show(Salarie $salarie):Response{
        return $this->render('salarie/show.html.twig',['salarie'=> $salarie]);
    }

    /**
     * @Route ("/test",name="salarie_test")
     */
    public function test(){
        echo "test";
        return $this->render('salarie/test.html.twig');

    }



}
