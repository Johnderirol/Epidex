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
}
