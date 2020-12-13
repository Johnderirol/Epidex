<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColorRepository::class)
 */
class Color
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=Collaborateur::class, mappedBy="color")
     */
    private $collaborateur;

    /**
     * @ORM\OneToMany(targetEntity=Rayon::class, mappedBy="color")
     */
    private $rayon;

    public function __construct()
    {
        $this->collaborateur = new ArrayCollection();
        $this->rayon = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Collaborateur[]
     */
    public function getCollaborateur(): Collection
    {
        return $this->collaborateur;
    }

    public function addCollaborateur(Collaborateur $collaborateur): self
    {
        if (!$this->collaborateur->contains($collaborateur)) {
            $this->collaborateur[] = $collaborateur;
            $collaborateur->setColor($this);
        }

        return $this;
    }

    public function removeCollaborateur(Collaborateur $collaborateur): self
    {
        if ($this->collaborateur->contains($collaborateur)) {
            $this->collaborateur->removeElement($collaborateur);
            // set the owning side to null (unless already changed)
            if ($collaborateur->getColor() === $this) {
                $collaborateur->setColor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rayon[]
     */
    public function getRayon(): Collection
    {
        return $this->rayon;
    }

    public function addRayon(Rayon $rayon): self
    {
        if (!$this->rayon->contains($rayon)) {
            $this->rayon[] = $rayon;
            $rayon->setColor($this);
        }

        return $this;
    }

    public function removeRayon(Rayon $rayon): self
    {
        if ($this->rayon->contains($rayon)) {
            $this->rayon->removeElement($rayon);
            // set the owning side to null (unless already changed)
            if ($rayon->getColor() === $this) {
                $rayon->setColor(null);
            }
        }

        return $this;
    }
}
