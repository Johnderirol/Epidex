<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MissionCibleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=MissionCibleRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class MissionCible
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
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Collaborateur::class, mappedBy="missionCible")
     * 
     */
    private $collaborateurs;

    /**
     * @ORM\ManyToMany(targetEntity=CompEtoile::class, inversedBy="missionCibles", cascade={"persist"})
     */
    private $competences;

    /**
     * @ORM\OneToMany(targetEntity=Leader::class, mappedBy="missionCible")
     */
    private $leaders;

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
        $this->collaborateurs = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->leaders = new ArrayCollection();
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
     * @return Collection|Collaborateur[]
     */
    public function getCollaborateurs(): Collection
    {
        return $this->collaborateurs;
    }

    public function addCollaborateur(Collaborateur $collaborateur): self
    {
        if (!$this->collaborateurs->contains($collaborateur)) {
            $this->collaborateurs[] = $collaborateur;
            $collaborateur->setMissionCible($this);
        }

        return $this;
    }

    public function removeCollaborateur(Collaborateur $collaborateur): self
    {
        if ($this->collaborateurs->contains($collaborateur)) {
            $this->collaborateurs->removeElement($collaborateur);
            // set the owning side to null (unless already changed)
            if ($collaborateur->getMissionCible() === $this) {
                $collaborateur->setMissionCible(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CompEtoile[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(CompEtoile $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(CompEtoile $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
        }

        return $this;
    }

    /**
     * @return Collection|Leader[]
     */
    public function getLeaders(): Collection
    {
        return $this->leaders;
    }

    public function addLeader(Leader $leader): self
    {
        if (!$this->leaders->contains($leader)) {
            $this->leaders[] = $leader;
            $leader->setMissionCible($this);
        }

        return $this;
    }

    public function removeLeader(Leader $leader): self
    {
        if ($this->leaders->removeElement($leader)) {
            // set the owning side to null (unless already changed)
            if ($leader->getMissionCible() === $this) {
                $leader->setMissionCible(null);
            }
        }

        return $this;
    }
}
