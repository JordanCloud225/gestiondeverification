<?php

namespace App\Entity;

use App\Repository\ControlefideliteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControlefideliteRepository::class)]
class Controlefidelite extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pointsdessai = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $valeurnominale = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $indicationlue = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $ecartreleve = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $ecartmaximalreleve = null;

    #[ORM\ManyToOne(inversedBy: 'controlefidelites')]
    private ?Instrument $instrument = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fidelitecorrecte = null;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsdessai(): ?int
    {
        return $this->pointsdessai;
    }

    public function setPointsdessai(int $pointsdessai): static
    {
        $this->pointsdessai = $pointsdessai;

        return $this;
    }

    public function getValeurnominale(): ?string
    {
        return $this->valeurnominale;
    }

    public function setValeurnominale(string $valeurnominale): static
    {
        $this->valeurnominale = $valeurnominale;

        return $this;
    }

    public function getIndicationlue(): ?string
    {
        return $this->indicationlue;
    }

    public function setIndicationlue(string $indicationlue): static
    {
        $this->indicationlue = $indicationlue;

        return $this;
    }

    public function getEcartreleve(): ?string
    {
        return $this->ecartreleve;
    }

    public function setEcartreleve(?string $ecartreleve): static
    {
        $this->ecartreleve = $ecartreleve;

        return $this;
    }

    public function getEcartmaximalreleve(): ?string
    {
        return $this->ecartmaximalreleve;
    }

    public function setEcartmaximalreleve(?string $ecartmaximalreleve): static
    {
        $this->ecartmaximalreleve = $ecartmaximalreleve;

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

    public function getFidelitecorrecte(): ?string
    {
        return $this->fidelitecorrecte;
    }

    public function setFidelitecorrecte(?string $fidelitecorrecte): static
    {
        $this->fidelitecorrecte = $fidelitecorrecte;

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
