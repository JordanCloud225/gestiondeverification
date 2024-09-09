<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateverification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numerocertificat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateemission = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $validite = null;

    #[ORM\ManyToOne(inversedBy: 'certifications')]
    private ?Instrument $instrument = null;

    #[ORM\ManyToOne(inversedBy: 'certifications')]
    private ?Conditionsetalonnage $conditionsetalonnage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateverification(): ?\DateTimeInterface
    {
        return $this->dateverification;
    }

    public function setDateverification(?\DateTimeInterface $dateverification): static
    {
        $this->dateverification = $dateverification;

        return $this;
    }

    public function getNumerocertificat(): ?string
    {
        return $this->numerocertificat;
    }

    public function setNumerocertificat(?string $numerocertificat): static
    {
        $this->numerocertificat = $numerocertificat;

        return $this;
    }

    public function getDateemission(): ?\DateTimeInterface
    {
        return $this->dateemission;
    }

    public function setDateemission(?\DateTimeInterface $dateemission): static
    {
        $this->dateemission = $dateemission;

        return $this;
    }

    public function getValidite(): ?\DateTimeInterface
    {
        return $this->validite;
    }

    public function setValidite(?\DateTimeInterface $validite): static
    {
        $this->validite = $validite;

        return $this;
    }

    public function getInstrument(): ?Instrument
    {
        return $this->instrument;
    }

    public function setInstrument(?Instrument $instrument): static
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getConditionsetalonnage(): ?Conditionsetalonnage
    {
        return $this->conditionsetalonnage;
    }

    public function setConditionsetalonnage(?Conditionsetalonnage $conditionsetalonnage): static
    {
        $this->conditionsetalonnage = $conditionsetalonnage;

        return $this;
    }
}
