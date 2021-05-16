<?php

namespace App\Entity;

use App\Repository\RatingLeaderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingLeaderRepository::class)
 */
class RatingLeader
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompLeader::class, inversedBy="ratingLeaders")
     */
    private $pole;

    /**
     * @ORM\ManyToOne(targetEntity=Leader::class, inversedBy="ratingLeaders")
     */
    private $leader;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="ratingLeaders")
     */
    private $collaborateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPole(): ?CompLeader
    {
        return $this->pole;
    }

    public function setPole(?CompLeader $pole): self
    {
        $this->pole = $pole;

        return $this;
    }

    public function getLeader(): ?Leader
    {
        return $this->leader;
    }

    public function setLeader(?Leader $leader): self
    {
        $this->leader = $leader;

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
