<?php

namespace App\Entity; 

use App\Repository\CollaborateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=CollaborateurRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *  fields={"matricule"},
 *  message="Ce collaborateur existe déjà"
 * )
 * 
 */
class Collaborateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * 
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     */
    private $prenom;

    /**
     * @ORM\ManyToOne(targetEntity=Mission::class, inversedBy="collaborateur")
     */
    private $mission;

    /**
     * @ORM\ManyToOne(targetEntity=Rayon::class, inversedBy="collaborateur")
     */
    private $rayon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(message="L'email doit être valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Les mots de passes ne correspondent pas")
     */
    public $passwordConfirm; 

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, minMessage="Votre description est trop courte")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Secteur::class, mappedBy="responsable")
     */
    private $secteurs;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users", cascade={"persist"})
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity=Evaluation::class, mappedBy="auteur")
     */
    private $auteurEvaluations;

    /**
     * @ORM\OneToMany(targetEntity=Evaluation::class, mappedBy="collaborateur")
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="collaborateur")
     */
    private $ratings;

    /**
     * @ORM\OneToMany(targetEntity=PDI::class, mappedBy="auteur")
     */
    private $PDIedit;

    /**
     * @ORM\OneToMany(targetEntity=PDI::class, mappedBy="collaborateur")
     */
    private $pDIs;

    /**
     * @ORM\OneToMany(targetEntity=Etoile::class, mappedBy="auteur")
     */
    private $etoiles;

    /**
     * @ORM\ManyToOne(targetEntity=MissionCible::class, inversedBy="collaborateurs")
     */
    private $missionCible;

    /**
     * @ORM\ManyToOne(targetEntity=Color::class, inversedBy="collaborateur")
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=RatingEtoile::class, mappedBy="collaborateur")
     */
    private $ratingEtoiles;

    public function getFullName(){
        return "{$this->prenom} {$this->nom}";
    }
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug(){
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->prenom . ' ' . $this->nom);
        }
    }

    public function __construct()
    {
        $this->secteurs = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->auteurEvaluations = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->PDIedit = new ArrayCollection();
        $this->pDIs = new ArrayCollection();
        $this->etoiles = new ArrayCollection();
        $this->ratingEtoiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    public function setMatricule(int $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getRayon(): ?Rayon
    {
        return $this->rayon;
    }

    public function setRayon(?Rayon $rayon): self
    {
        $this->rayon = $rayon;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Secteur[]
     */
    public function getSecteurs(): Collection
    {
        return $this->secteurs;
    }

    public function addSecteur(Secteur $secteur): self
    {
        if (!$this->secteurs->contains($secteur)) {
            $this->secteurs[] = $secteur;
            $secteur->setResponsable($this);
        }

        return $this;
    }

    public function removeSecteur(Secteur $secteur): self
    {
        if ($this->secteurs->contains($secteur)) {
            $this->secteurs->removeElement($secteur);
            // set the owning side to null (unless already changed)
            if ($secteur->getResponsable() === $this) {
                $secteur->setResponsable(null);
            }
        }

        return $this;
    }
    
    public function getRoles(){

        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }
    
    public function getPassword() {
        return $this->hash;
    }
    
    public function getSalt() {}
    
    public function getUsername() {
        return $this->email;
    }
    
    public function eraseCredentials() {}

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getAuteurEvaluations(): Collection
    {
        return $this->auteurEvaluations;
    }

    public function addAuteurEvaluation(Evaluation $auteurEvaluation): self
    {
        if (!$this->auteurEvaluations->contains($auteurEvaluation)) {
            $this->auteurEvaluations[] = $auteurEvaluation;
            $auteurEvaluation->setAuteur($this);
        }

        return $this;
    }

    public function removeAuteurEvaluation(Evaluation $auteurEvaluation): self
    {
        if ($this->auteurEvaluations->contains($auteurEvaluation)) {
            $this->auteurEvaluations->removeElement($auteurEvaluation);
            // set the owning side to null (unless already changed)
            if ($auteurEvaluation->getAuteur() === $this) {
                $auteurEvaluation->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setCollaborateur($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getCollaborateur() === $this) {
                $evaluation->setCollaborateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setCollaborateur($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getCollaborateur() === $this) {
                $rating->setCollaborateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PDI[]
     */
    public function getPDIedit(): Collection
    {
        return $this->PDIedit;
    }

    public function addPDIedit(PDI $pDIedit): self
    {
        if (!$this->PDIedit->contains($pDIedit)) {
            $this->PDIedit[] = $pDIedit;
            $pDIedit->setAuteur($this);
        }

        return $this;
    }

    public function removePDIedit(PDI $pDIedit): self
    {
        if ($this->PDIedit->contains($pDIedit)) {
            $this->PDIedit->removeElement($pDIedit);
            // set the owning side to null (unless already changed)
            if ($pDIedit->getAuteur() === $this) {
                $pDIedit->setAuteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PDI[]
     */
    public function getPDIs(): Collection
    {
        return $this->pDIs;
    }

    public function addPDI(PDI $pDI): self
    {
        if (!$this->pDIs->contains($pDI)) {
            $this->pDIs[] = $pDI;
            $pDI->setCollaborateur($this);
        }

        return $this;
    }

    public function removePDI(PDI $pDI): self
    {
        if ($this->pDIs->contains($pDI)) {
            $this->pDIs->removeElement($pDI);
            // set the owning side to null (unless already changed)
            if ($pDI->getCollaborateur() === $this) {
                $pDI->setCollaborateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Etoile[]
     */
    public function getEtoiles(): Collection
    {
        return $this->etoiles;
    }

    public function addEtoile(Etoile $etoile): self
    {
        if (!$this->etoiles->contains($etoile)) {
            $this->etoiles[] = $etoile;
            $etoile->setAuteur($this);
        }

        return $this;
    }

    public function removeEtoile(Etoile $etoile): self
    {
        if ($this->etoiles->contains($etoile)) {
            $this->etoiles->removeElement($etoile);
            // set the owning side to null (unless already changed)
            if ($etoile->getAuteur() === $this) {
                $etoile->setAuteur(null);
            }
        }

        return $this;
    }

    public function getMissionCible(): ?MissionCible
    {
        return $this->missionCible;
    }

    public function setMissionCible(?MissionCible $missionCible): self
    {
        $this->missionCible = $missionCible;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|RatingEtoile[]
     */
    public function getRatingEtoiles(): Collection
    {
        return $this->ratingEtoiles;
    }

    public function addRatingEtoile(RatingEtoile $ratingEtoile): self
    {
        if (!$this->ratingEtoiles->contains($ratingEtoile)) {
            $this->ratingEtoiles[] = $ratingEtoile;
            $ratingEtoile->setCollaborateur($this);
        }

        return $this;
    }

    public function removeRatingEtoile(RatingEtoile $ratingEtoile): self
    {
        if ($this->ratingEtoiles->contains($ratingEtoile)) {
            $this->ratingEtoiles->removeElement($ratingEtoile);
            // set the owning side to null (unless already changed)
            if ($ratingEtoile->getCollaborateur() === $this) {
                $ratingEtoile->setCollaborateur(null);
            }
        }

        return $this;
    }
}
