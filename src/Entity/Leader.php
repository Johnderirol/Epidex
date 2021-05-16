<?php

namespace App\Entity;

use App\Repository\LeaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeaderRepository::class)
 */
class Leader
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="auteurLeader")
     */
    private $auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="leaders")
     */
    private $collaborateur;

    /**
     * @ORM\ManyToOne(targetEntity=MissionCible::class, inversedBy="leaders")
     */
    private $missionCible;

    /**
     * @ORM\OneToMany(targetEntity=RatingLeader::class, mappedBy="leader", cascade={"persist", "remove"})
     */
    private $ratingLeaders;

    public function __construct()
    {
        $this->ratingLeaders = new ArrayCollection();
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

    public function getMissionCible(): ?MissionCible
    {
        return $this->missionCible;
    }

    public function setMissionCible(?MissionCible $missionCible): self
    {
        $this->missionCible = $missionCible;

        return $this;
    }

    /**
     * @return Collection|RatingLeader[]
     */
    public function getRatingLeaders(): Collection
    {
        return $this->ratingLeaders;
    }

    public function addRatingLeader(RatingLeader $ratingLeader): self
    {
        if (!$this->ratingLeaders->contains($ratingLeader)) {
            $this->ratingLeaders[] = $ratingLeader;
            $ratingLeader->setLeader($this);
        }

        return $this;
    }

    public function removeRatingLeader(RatingLeader $ratingLeader): self
    {
        if ($this->ratingLeaders->removeElement($ratingLeader)) {
            // set the owning side to null (unless already changed)
            if ($ratingLeader->getLeader() === $this) {
                $ratingLeader->setLeader(null);
            }
        }

        return $this;
    }
}
