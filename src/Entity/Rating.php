<?php

namespace App\Entity;

use App\Entity\Skill;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RatingRepository;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Skill::class, inversedBy="ratings")
     */
    private $competences;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Evaluation::class, inversedBy="ratings")
     */
    private $evaluation;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="ratings")
     */
    private $collaborateur;

    /**
     * @ORM\ManyToOne(targetEntity=Rayon::class, inversedBy="ratings")
     */
    private $rayon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetences(): ?Skill
    {
        return $this->competences;
    }

    public function setCompetences(?Skill $competences): self
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

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): self
    {
        $this->evaluation = $evaluation;

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

    public function getRayon(): ?Rayon
    {
        return $this->rayon;
    }

    public function setRayon(?Rayon $rayon): self
    {
        $this->rayon = $rayon;

        return $this;
    }
}
