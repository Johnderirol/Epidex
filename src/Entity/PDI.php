<?php

namespace App\Entity;

use App\Repository\PDIRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PDIRepository::class)
 */
class PDI
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="PDIedit")
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $situationApprenante;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $progres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contributeursRoles;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=Collaborateur::class, inversedBy="pDIs")
     */
    private $collaborateur;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSituationApprenante(): ?string
    {
        return $this->situationApprenante;
    }

    public function setSituationApprenante(string $situationApprenante): self
    {
        $this->situationApprenante = $situationApprenante;

        return $this;
    }

    public function getProgres(): ?string
    {
        return $this->progres;
    }

    public function setProgres(string $progres): self
    {
        $this->progres = $progres;

        return $this;
    }

    public function getContributeursRoles(): ?string
    {
        return $this->contributeursRoles;
    }

    public function setContributeursRoles(string $contributeursRoles): self
    {
        $this->contributeursRoles = $contributeursRoles;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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
