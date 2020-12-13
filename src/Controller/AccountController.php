<?php

namespace App\Controller;

use App\Form\RayonType;
use App\Form\MissionType;
use App\Entity\Collaborateur;
use App\Entity\PasswordUpdate;
use App\Entity\Rating;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\PDIRepository;
use App\Repository\ColorRepository;
use App\Repository\EtoileRepository;
use Symfony\Component\Form\FormError;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use App\Repository\RatingEtoileRepository;
use App\Repository\RatingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Affiche et génère le formulaire de connexion
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
    
        return $this->render('account/login.html.twig', [
            'controller_name' => 'AccountController',
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }
    
    
    /**
     * Permet de se déconnecter
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout() {
        
    }
    
    
    /**
     * Permet d'afficher le formulaire d'inscription
     * @Route("/register", name="account_register")
     * @Route("/register/{id}/edit", name="edit_register_collab")
     * 
     * @return Response
     */
    public function register(Collaborateur $collaborateur=null, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, ColorRepository $repoColor)
        
    {
        
        if(!$collaborateur){
            $collaborateur = new Collaborateur();
        }

        $collabColor = $collaborateur->getColor();
        if(empty($collabColor)){
            $randColor = rand(0,239);
            $color = $repoColor->findOneById($randColor);
        } else {$color = $collabColor;}
        
        
        $form = $this->createForm(RegistrationType::class, $collaborateur);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){   
            $hash = $encoder->encodePassword($collaborateur, $collaborateur->getHash());
            $collaborateur->setHash($hash);
            $collaborateur->setColor($color);
            $manager->persist($collaborateur);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Le collaborateur a été ajouté !!!"
            );
            
            
            return $this->redirectToRoute('user_show', [
                'id' => $collaborateur->getId()
            ]);
        }
        
        return $this->render('account/registration.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $collaborateur->getId() !== null
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //1. Vérifier que le oldPassword du formulaire soit le même que le password User
            $oldPassword = $passwordUpdate->getOldPassword();
            $isPasswordValid = $encoder->isPasswordValid($user, $oldPassword);
            if($isPasswordValid === false) {
                //Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Ce mot de passe n'est pas le bon"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash =$encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);
                
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le mot de passe a bien été mis à jour"
                );

                return $this->redirectToRoute('dashboard');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil User
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount(PDIRepository $repoPDI, CategorieRepository $repoCat, EtoileRepository $repoEtoile, RatingRepository $repoRat, RatingEtoileRepository $repoRatetoile)
    {
        $user = $this->getUser(); 
        $pdis = $repoPDI->findByCollaborateur($user);
        $categorie = $repoCat->findAll();
        $etoile = $repoEtoile->findByCollaborateur($user);
        $ratings = $repoRat->findAll();
        $ratingEtoiles = $repoRatetoile->findAll();

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'categories'=>$categorie,
            'pdis' => $pdis,
            'etoiles' => $etoile,
            'ratings'=>$ratings,
            'ratingEtoiles'=>$ratingEtoiles,
        ]);
    }

    /**
     * Undocumented function
     * @Route("/account/{id}/delete", name="user_delete")
     * @Security("is_granted('ROLE_ADMIN')", message="Vous n'avez pas le droit de supprimer un collaborateur, Contactez l'admin")
     * @param Collaborateur $collaborateur
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Collaborateur $collaborateur, EntityManagerInterface $manager) {
        $manager->remove($collaborateur);
        $manager->flush();

        $this->addFlash(
            'success',
            "{$collaborateur->getFullName()} a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute("admin_collab");
    }

}
