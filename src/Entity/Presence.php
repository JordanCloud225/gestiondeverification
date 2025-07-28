<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresenceRepository::class)]
class Presence extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'presences')]
    private ?Intervention $intervention = null;

    #[ORM\ManyToOne(inversedBy: 'presences')]
    private ?Technicien $technicien = null;

    #[ORM\Column(nullable: true)]
    private ?bool $present = null;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntervention(): ?intervention
    {
        return $this->intervention;
    }

    public function setIntervention(?intervention $intervention): static
    {
        $this->intervention = $intervention;

        return $this;
    }

    public function getTechnicien(): ?technicien
    {
        return $this->technicien;
    }

    public function setTechnicien(?technicien $technicien): static
    {
        $this->technicien = $technicien;

        return $this;
    }

    public function isPresent(): ?bool
    {
        return $this->present;
    }

    public function setPresent(?bool $present): static
    {
        $this->present = $present;

        return $this;
    }

    public function getIdentreprise(): ?int
    {
        return $this->identreprise;
    }

    public function setIdentreprise(?int $identreprise): static
    {
        $this->identreprise = $identreprise;

        return $this;
    }
}
