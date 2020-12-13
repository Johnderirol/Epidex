<?php
 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Collaborateur;
use App\Entity\Mission;
use App\Entity\Secteur;
use App\Entity\Rayon;
use App\Repository\CollaborateurRepository;
use App\Repository\MissionRepository;
use App\Repository\SecteurRepository;
use App\Form\CollaborateurType;





class EffectifController extends AbstractController
{
     /**
     * @Route("/effectif/nouveau", name="new_collab")
     * @Route("/effectif/{matricule}/edit", name="edit_collab") 
     */
    public function create (Collaborateur $collaborateur=null, Request $request, EntityManagerInterface $manager)
        
    {
        if(!$collaborateur){
            $collaborateur = new Collaborateur();
        }
        
        $form = $this->createForm(CollaborateurType::class, $collaborateur);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){        
            $manager->persist($collaborateur);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Le collaborateur a été ajouté !!!"
            );
            
            
            return $this->redirectToRoute('collab', [
                'id' => $collaborateur->getId()
            ]);
        }
        
        return $this->render('effectif/create.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $collaborateur->getId() !== null
        ]);
    }
    
    /**
     * @Route("/effectif", name="effectif")
     */
    public function index(CollaborateurRepository $repo)
    {
        $effectif = $repo->findAll();
        
        return $this->render('effectif/index.html.twig', [
            'controller_name' => 'EffectifController',
            'collaborateurs' => $effectif
        ]);
    }
    
    /**
     * @Route("/effectif/collab/{id}", name="collab")
     * 
     */
    public function showCollab(Collaborateur $collaborateur) 
    {

        //$collaborateurs = $repo->findOneById($id);
        
        return $this->render('effectif/collab.html.twig',[
            'collaborateur'=>$collaborateur
        ]);
    }
    
    
    /**
     * @Route("/effectif/mission/{id}", name="mission")
     */
    public function showMission($id, Mission $mission, EntityManagerInterface $manager) 
    {
        $query = $manager->createQuery(
            "SELECT AVG(r.note) as note, s.title
            FROM App\Entity\Collaborateur c
            JOIN c.evaluations e
            JOIN c.mission m
            JOIN e.ratings r
            JOIN r.competences s
            WHERE m.id =$id
            GROUP BY s.title");
        $evalMission= $query->getResult();

        return $this->render('effectif/mission.html.twig',[
            'mission'=>$mission,
            'evalMission'=>$evalMission
        ]);
    }
    
    
}