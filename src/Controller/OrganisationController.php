<?php

namespace App\Controller;

use App\Entity\Rayon;
use App\Entity\Secteur;
use App\Form\RayonType;
use App\Form\SecteurType;
use App\Repository\ColorRepository;
use App\Repository\RayonRepository;
use App\Repository\SecteurRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/organisation")
 * @IsGranted("ROLE_ADMIN")
 */
class OrganisationController extends AbstractController
{

    /**
     * @Route("/rayon/nouveau", name="new_rayon")
     * @Route("/rayon/{slug}/edit", name="edit_rayon") 
     */
    public function createRayon (Rayon $rayon=null, Request $request, EntityManagerInterface $manager, ColorRepository $repoColor)
    {
        if(!$rayon){
            $rayon = new Rayon();
        }

        //Gestiond es couleurs rayons
        $rayonColor = $rayon->getColor();
        if(empty($rayonColor)){
            $randColor = rand(0,239);
            $color = $repoColor->findOneById($randColor);
        } else {$color = $rayonColor;}
        

        $form = $this->createForm(RayonType::class, $rayon);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $rayon->setColor($color);  
            $manager->persist($rayon);
            $manager->flush(); 
            
            $this->addFlash(
                'success',
                "Le rayon a été ajouté  !!!"
            );

            return $this->redirectToRoute('organisation');
        }
        
        return $this->render('admin/organisation/nouveaurayon.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $rayon->getSlug() !== null
        ]);
    }
            
    /**
     * @Route("/secteur/nouveau", name="new_secteur")
     * @Route("/{slug}/edit", name="edit_secteur") 
     */
    public function createSecteur (Secteur $secteur=null, Request $request, EntityManagerInterface $manager)
    {
        if(!$secteur){
            $secteur = new Secteur();
        }

        $form = $this->createForm(SecteurType::class, $secteur);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){ 
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($secteur);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Le secteur a été ajouté !!!"
            );
            
            
            return $this->redirectToRoute('detail_secteur', [
                'slug' => $secteur->getSlug()
            ]);
        }
        
        return $this->render('admin/organisation/nouveausecteur.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $secteur->getSlug() !== null
        ]);
    }
    
    /**
     * @Route("/secteur/{slug}", name="detail_secteur")
     */
    public function showSecteur($slug, SecteurRepository $repo)
    {
        //Je récupère l'annonce qui correspond au slug
        $secteur = $repo->findOneBySlug($slug);
        
        return $this->render('admin/organisation/secteurdetail.html.twig', [
            'secteur'=>$secteur
        ]);
    }
    
    
    /**
     * @Route("/", name="organisation")
     */
    public function index(SecteurRepository $repo, RayonRepository $repoRay) 
    {
        
        $secteur = $repo->findAll();    
        $rayon = $repoRay->findAll();   
        
        return $this->render('admin/organisation/index.html.twig',[
            'controller_name' => 'EffectifController',
            'secteur'=>$secteur,
            'rayon'=>$rayon
        ]);
    }

    /**
     * @Route("/secteur", name="indexSecteur")
     */
    public function indexSecteur(SecteurRepository $repo) 
    {
        
        $secteur = $repo->findAll();    
        
        return $this->render('admin/organisation/indexSecteur.html.twig',[
            'controller_name' => 'EffectifController',
            'secteur'=>$secteur        ]);
    }

    /**
     * @Route("/rayon", name="indexRayon")
     */
    public function indexRayon(RayonRepository $repo) 
    {
        
        $rayon = $repo->findAll();    
        
        return $this->render('admin/organisation/indexRayon.html.twig',[
            'controller_name' => 'EffectifController',
            'rayon'=>$rayon       
            ]);
    }

    /**
     * @Route("/secteur/{id}/delete", name="delete_secteur")
     * @param Secteur $secteur
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Secteur $secteur, EntityManagerInterface $manager)
    {
        if(count($secteur->getRayons())>0){
            $this->addFlash(
                'warning',
                "Supprimer d'abord les rayons dans l'édit de secteur"
            );
        }

        else {
        $manager->remove($secteur);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le secteur a été supprimé !!!"
        );
        }
        return $this->redirectToRoute('organisation');
    }
}
