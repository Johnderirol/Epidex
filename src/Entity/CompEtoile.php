<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompEtoileRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CompEtoileRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class CompEtoile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=MissionCible::class, mappedBy="competences", cascade={"persist"})
     */
    private $missionCibles;

    /**
     * @ORM\OneToMany(targetEntity=RatingEtoile::class, mappedBy="competences", orphanRemoval=true)
     */
    private $comp;


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug(){
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function __construct()
    {
        $this->missionCibles = new ArrayCollection();
        $this->comp = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
     * @return Collection|MissionCible[]
     */
    public function getMissionCibles(): Collection
    {
        return $this->missionCibles;
    }

    public function addMissionCible(MissionCible $missionCible): self
    {
        if (!$this->missionCibles->contains($missionCible)) {
            $this->missionCibles[] = $missionCible;
            $missionCible->addCompetence($this);
        }

        return $this;
    }

    public function removeMissionCible(MissionCible $missionCible): self
    {
        if ($this->missionCibles->contains($missionCible)) {
            $this->missionCibles->removeElement($missionCible);
            $missionCible->removeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|RatingEtoile[]
     */
    public function getComp(): Collection
    {
        return $this->comp;
    }

    public function addComp(RatingEtoile $comp): self
    {
        if (!$this->comp->contains($comp)) {
            $this->comp[] = $comp;
            $comp->setCompetences($this);
        }

        return $this;
    }

    public function removeComp(RatingEtoile $comp): self
    {
        if ($this->comp->contains($comp)) {
            $this->comp->removeElement($comp);
            // set the owning side to null (unless already changed)
            if ($comp->getCompetences() === $this) {
                $comp->setCompetences(null);
            }
        }

        return $this;
    }
}
