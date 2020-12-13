<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Entity\Collaborateur;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCollabController extends AbstractController
{
    /**
     * @Route("/admin/collaborateur", name="admin_collab")
     */
    public function index(CollaborateurRepository $repo)
    {
        $collaborateur = $repo->findAll();

        return $this->render('admin/collaborateur/index.html.twig', [
            'collaborateur' => $collaborateur,
        ]);
    }

    /**
     * @Route("/admin/collaborateur/roles", name="admin_role")
     */
    public function roles(RoleRepository $repoRole, CollaborateurRepository $repo)
    {
        $roles = $repoRole->findAll();
        $collaborateur = $repo->findAll();
 
        return $this->render('admin/collaborateur/roles.html.twig', [
            'roles' => $roles,
            'collaborateurs' => $collaborateur,
        ]);
    }
    

    /**
     * Permet de suppimer une étoile des compétences
     * @Route("/admin/collaborateur/roles/delete/{collab}/{role}", name="role_delete")
     * @param Collaborateur $collaborateur
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete($collab, $role, CollaborateurRepository $repo, RoleRepository $repoRole, EntityManagerInterface $manager) {
        $collaborateur = $repo->findOneById($collab);
        $roles = $repoRole->findOneById($role);
        $collaborateur->removeUserRole($roles);
        $manager->persist($collaborateur);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le rôle du collaborateur a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_role');
    }
    
}
