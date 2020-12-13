<?php

namespace App\Entity;

use App\Repository\RatingEtoileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingEtoileRepository::class)
 */
class RatingEtoile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompEtoile::class, inversedBy="note")
     */
    private $competences;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Etoile::class, inversedBy="ratingEtoiles")
     */
    private $etoile;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="ratingEtoiles")
     */
    private $collaborateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetences(): ?CompEtoile
    {
        return $this->competences;
    }

    public function setCompetences(?CompEtoile $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getEtoile(): ?Etoile
    {
        return $this->etoile;
    }

    public function setEtoile(?Etoile $etoile): self
    {
        $this->etoile = $etoile;

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
}
