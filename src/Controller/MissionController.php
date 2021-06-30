<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\CategorieRepository;
use App\Repository\CollaborateurRepository;
use App\Repository\MissionRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/competences/mission")
 * @IsGranted("ROLE_ADMIN")
 */
class MissionController extends AbstractController
{
    /**
     * @Route("/", name="mission_index", methods={"GET"})
     */
    
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('admin/competences/mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mission_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mission);
            $entityManager->flush();

            return $this->redirectToRoute('mission_index');
        }

        return $this->render('admin/competences/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mission_show", methods={"GET"})
     */
    public function showMission($id, Mission $mission, CollaborateurRepository $repoCollab, SkillRepository $skillRepo, CategorieRepository $catRepo, EntityManagerInterface $manager) 
    {
        $collaborateurs = $repoCollab->findByMission($id);
        $cat = $catRepo->findAll();
        $skills = $skillRepo->findSkillIdByMission($id);
        $evalMission = $skillRepo->findAvgNotesByMission($id);
        
        //on récupère les données de chaque collab dans un seul tableau
        $IdColab = [];
        foreach ($collaborateurs as $collabid) {
            $IdColab[] = $collabid['id'];
        }   
        $skillColab = [];
        foreach ($IdColab as $collabid) {
            $skillColab[] = $skillRepo->findNotesByCollab($collabid);
        }   
        
        //on nomme les clés de skills avec SkillId
        $skills = array_column($skills, null, 'skillId');

        //après avoir compté le nombre de collab, nous fusionnons les collab avec les compétences
        $countCol = count($skillColab);
        $out = [];
        for ($i = 0; $i <= $countCol; $i++)  {
            if(empty($skillColab[$i])){
            } else {
            $out[$i] = array_column($skillColab[$i], null, 'skillId');
            $out[$i] = array_replace($skills, $out[$i]);
            }
        }
        
        //on renome les clés du tableau final avec les identifiants des collaborateurs
        $num = [];
        foreach($skillColab as $sk) {
            foreach ($sk as $key => $value) {
                $num[] = $value['proprioID'];
            }
        }
        $num = array_unique($num);
        $countNum = count($num);
        for ($i = 0; $i <= $countNum; $i++) {
            $out = array_combine($num, $out);
        }
        dump($out);
        return $this->render('admin/competences/mission/show.html.twig',[
            'collaborateurs' => $collaborateurs, 
            'categories'=>$cat,
            'mission'=>$mission,
            'skills'=>$evalMission,
            'ratings'=>$out,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mission_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mission $mission): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_index');
        }

        return $this->render('admin/competences/mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mission_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mission $mission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mission_index');
    }
}
