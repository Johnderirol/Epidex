<?php

namespace App\Entity;

use App\Repository\EtoileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtoileRepository::class)
 */
class Etoile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="etoiles")
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="etoiles")
     */
    private $collaborateur;

    /**
     * @ORM\OneToMany(targetEntity=RatingEtoile::class, mappedBy="etoile", cascade={"persist", "remove"})
     */
    private $ratingEtoiles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $missionProjet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $retours;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comprehension;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $atouts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $axes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstActions;

    public function __construct()
    {
        $this->ratingEtoiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuteur(): ?Collaborateur
    {
        return $this->auteur;
    }

    public function setAuteur(?Collaborateur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getCollaborateur(): ?Collaborateur
    {
        return $this->collaborateur;
    }

    public function setCollaborateur(?Collaborateur $collaborateur): self
    {
        $this->collaborateur = $collaborateur;

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
            $ratingEtoile->setEtoile($this);
        }

        return $this;
    }

    public function removeRatingEtoile(RatingEtoile $ratingEtoile): self
    {
        if ($this->ratingEtoiles->contains($ratingEtoile)) {
            $this->ratingEtoiles->removeElement($ratingEtoile);
            // set the owning side to null (unless already changed)
            if ($ratingEtoile->getEtoile() === $this) {
                $ratingEtoile->setEtoile(null);
            }
        }

        return $this;
    }

    public function getMissionProjet(): ?string
    {
        return $this->missionProjet;
    }

    public function setMissionProjet(string $missionProjet): self
    {
        $this->missionProjet = $missionProjet;

        return $this;
    }

    public function getRetours(): ?string
    {
        return $this->retours;
    }

    public function setRetours(?string $retours): self
    {
        $this->retours = $retours;

        return $this;
    }

    public function getComprehension(): ?string
    {
        return $this->comprehension;
    }

    public function setComprehension(string $comprehension): self
    {
        $this->comprehension = $comprehension;

        return $this;
    }

    public function getAtouts(): ?string
    {
        return $this->atouts;
    }

    public function setAtouts(?string $atouts): self
    {
        $this->atouts = $atouts;

        return $this;
    }

    public function getAxes(): ?string
    {
        return $this->axes;
    }

    public function setAxes(?string $axes): self
    {
        $this->axes = $axes;

        return $this;
    }

    public function getFirstActions(): ?string
    {
        return $this->firstActions;
    }

    public function setFirstActions(?string $firstActions): self
    {
        $this->firstActions = $firstActions;

        return $this;
    }
}
