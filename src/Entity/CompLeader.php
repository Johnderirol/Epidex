<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompLeaderRepository;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @ORM\Entity(repositoryClass=CompLeaderRepository::class)
 */
class CompLeader
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pole;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $definition;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPole(): ?string
    {
        return $this->pole;
    }

    public function setPole(string $pole): self
    {
        $this->pole = $pole;

        return $this;
    }

    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    public function setDefinition(string $definition): self
    {
        $this->definition = $definition;

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
            $ratingLeader->setPole($this);
        }

        return $this;
    }

    public function removeRatingLeader(RatingLeader $ratingLeader): self
    {
        if ($this->ratingLeaders->removeElement($ratingLeader)) {
            // set the owning side to null (unless already changed)
            if ($ratingLeader->getPole() === $this) {
                $ratingLeader->setPole(null);
            }
        }

        return $this;
    }
}
