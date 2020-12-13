<?php

namespace App\Controller;

use App\Entity\Rayon;
use App\Entity\Secteur;
use App\Form\RayonType;
use App\Form\SecteurType;
use App\Repository\ColorRepository;
use App\Repository\RayonRepository;
use App\Repository\SecteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/organisation")
 */
class OrganisationController extends AbstractController
{
            
    /**
     * @Route("/secteur/nouveau", name="new_secteur")
     * @Route("/{slug}/edit", name="edit_secteur") 
     */
    public function createSecteur (Secteur $secteur=null, Request $request, EntityManagerInterface $manager, ColorRepository $repoColor)
    {
        if(!$secteur){
            $secteur = new Secteur();
        }

        $form = $this->createForm(SecteurType::class, $secteur);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){ 
            foreach ($secteur->getRayons() as $rayon){
                $rayon->SetSecteur($secteur);
                $rayonColor = $rayon->getColor();
                if(empty($rayonColor)){
                    $randColor = rand(0,239);
                    $color = $repoColor->findOneById($randColor);
                } else {$color = $rayonColor;}
                $rayon->setColor($color);
                $manager->persist($rayon);
            }
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
    public function index(SecteurRepository $repo) 
    {
        
        $secteur = $repo->findAll();       
        
        return $this->render('admin/organisation/index.html.twig',[
            'controller_name' => 'EffectifController',
            'secteur'=>$secteur
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
