<?php

namespace App\Controller;

use App\Entity\PDI;
use App\Form\CollabPDIType;
use App\Repository\PDIRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PDIController extends AbstractController
{
    /**
     * @Route("/admin/pdi", name="admin_pdi")
     * @IsGranted("ROLE_ADMIN")
     * @Route("/manager/pdi", name="manager_pdi")
     * @IsGranted("ROLE_MANAGER")
     */
    public function index(PDIRepository $repo)
    {

        $pdi = $repo->findAll();

        return $this->render('pdi/index.html.twig', [
            'pdis' => $pdi,
        ]);

    } 

    /**
     * Permet de faire un nouveau PDI
     * @Route("/admin/new/{id}", name="admin_pdi_new", methods={"GET","POST"})
     * @Route("/manager/new/{id}", name="manager_pdi_new", methods={"GET","POST"})
     * Permet d'éditer un PDI
     * @Route("/admin/edit/{id}", name="admin_pdi_edit", methods={"GET","POST"})
     * @Route("/manager/edit/{id}", name="manager_pdi_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     */
    public function new($id, PDI $PDI=null, Request $request, EntityManagerInterface $manager, CollaborateurRepository $repoCollab): Response
    {
        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        $collaborateur = $repoCollab->findOneById($id);
        if(!$PDI){
            $PDI = new PDI();
        }
        $form = $this->createForm(CollabPDIType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($collaborateur->getPDIs() as $PDI){
                $PDI->setAuteur($this->getUser());
                $PDI->setCollaborateur($collaborateur);
                $PDI->setCreatedAt(new \DateTime());
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($PDI);
            }
            $manager->persist($collaborateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les Plan de Développement a été ajouté !!!");

            if ($user[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_pdi');
            }
            elseif ($user[0] == 'ROLE_MANAGER') {
                return $this->redirectToRoute('manager_pdi');
            }
        }

        return $this->render('pdi/new.html.twig', [
            'pdi' => $PDI,
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/{id}", name="admin_pdi_show", methods={"GET"})
     * @Route("/manager/{id}", name="manager_pdi_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     */
    public function show($id, PDIRepository $repoPdi, CollaborateurRepository $repoCollab): Response
    {
        $collaborateur = $repoCollab->findById($id);
        dump($collaborateur);
        $pdis = $repoPdi->findByCollaborateur($collaborateur);
        dump($pdis);

        return $this->render('pdi/show.html.twig', [
            'pdis' => $pdis,
            'collaborateurs'=> $collaborateur,

        ]);

    }

    /**
     * Permet de suppimer une évaluation
     * @Route("/admin/delete/{id}", name="admin_pdi_delete", requirements={"id":"\d+"}))
     * @Route("/manager/delete/{id}/", name="manager_pdi_delete", requirements={"id":"\d+"}))
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     * @param PDI $pdi
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(PDI $pdi, EntityManagerInterface $manager) : Response
    {
        //Récupérer l'user
        $user = $this->getUser()->getRoles();
        $manager->remove($pdi);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le PDI a bien été supprimé de la base de donnée !"
        );

        if ($user[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin_pdi');
        }
        elseif ($user[0] == 'ROLE_MANAGER') {
            return $this->redirectToRoute('manager_pdi');
        }
        
    }
}
