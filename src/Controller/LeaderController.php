<?php

namespace App\Controller;

use App\Entity\Leader;
use App\Form\LeaderType;
use App\Entity\CompLeader;
use App\Entity\RatingLeader;
use App\Form\RatingLeaderType;
use App\Repository\LeaderRepository;
use App\Repository\CompLeaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LeaderController extends AbstractController
{
    /**
     * @Route("/admin/leader", name="admin_leader")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(LeaderRepository $leaderRepository): Response
    {
        return $this->render('leader/index.html.twig', [
            'leaders' => $leaderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="leader_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER') ")
     */
    public function new($id, Request $request, CollaborateurRepository $repoCollab, EntityManagerInterface $manager, CompLeaderRepository $repoComp): Response
    {
        //Récupérer l'user
        $user = $this->getUser()->getRoles();
        //Récupérer Collaborateur grâce au Id
        $collaborateur = $repoCollab->findOneById($id);
        
        //Nouvelle évaluation
        $leader = new Leader();

        //On récupère les poles de l'étoile du leader
        $compLead = $repoComp->findAll();
        dump($compLead);
        dump($collaborateur);

        //On demande un array de compétences sélectionnées dans $compLeader
        foreach ($compLead as $compId) {
            $rating = new RatingLeader ();
            $compT = $repoComp->findOneById($compId);
            $rating ->setPole($compT);
            $leader->addRatingLeader($rating);
            }

        $form = $this->createForm(LeaderType::class, $leader);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ 
            foreach ($leader->getRatingLeaders() as $rating){
                $rating->setLeader($leader);
                $rating->setCollaborateur($collaborateur);
                $manager->persist($rating);
            }
            $leader->setAuteur($this->getUser());
            $leader->setCollaborateur($collaborateur);
            $leader->setCreatedAt(new \DateTime());
            $manager->persist($leader);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'évaluation est prise en compte !!!");

            return $this->redirectToRoute('leader_index');
        }

        return $this->render('leader/new.html.twig', [
            'leader' => $leader,
            'collaborateur' => $collaborateur,
            'comps'=>$compLead,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit/{slug}", name="leader_edit")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER') ")
     */
    public function edit($slug, Request $request, Leader $leader,CollaborateurRepository $repoCollab, CompLeaderRepository $repoComp): Response
    {
        $comp = $repoComp->findAll();
        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        $collaborateur = $repoCollab->findOneBySlug($slug);
        $form = $this->createForm(LeaderType::class, $leader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($user[0] = 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_leader');
            }
            elseif ($user[0] = 'ROLE_MANAGER') {
                return $this->redirectToRoute('manager_eval');
            }
            else {
                return $this->redirectToRoute('account_index');
            }
        }

        return $this->render('leader/edit.html.twig', [
            'leader' => $leader,
            'comps'=>$comp,
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }

    /** 
     * @Route("/{id}", name="leader_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     */
    public function show(Leader $leader, CompLeaderRepository $repoComp): Response 
    {
        //On récupère les poles de l'étoile du leader
        $compLead = $repoComp->findAll();

        return $this->render('leader/show.html.twig', [
            'comp'=> $compLead,
            'leader' => $leader,
        ]);
    }
       
    /**
     * Permet de suppimer une évaluation
     * @Route("/admin/delete_leader/{id}", name="admin_leader_delete")
     * @Route("/manager/delete_leader/{id}", name="manager_leader_delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     * @param Leader $leader
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Leader $leader, EntityManagerInterface $manager) {
        $manager->remove($leader);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'étoile du leader a bien été supprimé de la base de donnée !"
        );

        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        if ($user[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin_leader');
        }
        elseif ($user[0] == 'ROLE_MANAGER') {
            return $this->redirectToRoute('manager_eval');
        }
    }
}
