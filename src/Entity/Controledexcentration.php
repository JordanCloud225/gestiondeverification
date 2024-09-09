<?php

namespace App\Entity;

use App\Repository\ControledexcentrationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControledexcentrationRepository::class)]
class Controledexcentration extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numposition = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $valeurnominale = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $indicationlue = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $indicationsurcharge = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $ecartreleve = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $croquisetposition = null;

    #[ORM\ManyToOne(inversedBy: 'controledexcentrations')]
    private ?Instrument $instrument = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $excentrationcorrecte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumposition(): ?int
    {
        return $this->numposition;
    }

    public function setNumposition(int $numposition): static
    {
        $this->numposition = $numposition;

        return $this;
    }

    public function getValeurnominale(): ?string
    {
        return $this->valeurnominale;
    }

    public function setValeurnominale(?string $valeurnominale): static
    {
        $this->valeurnominale = $valeurnominale;

        return $this;
    }

    public function getIndicationlue(): ?string
    {
        return $this->indicationlue;
    }

    public function setIndicationlue(?string $indicationlue): static
    {
        $this->indicationlue = $indicationlue;

        return $this;
    }

    public function getIndicationsurcharge(): ?string
    {
        return $this->indicationsurcharge;
    }

    public function setIndicationsurcharge(?string $indicationsurcharge): static
    {
        $this->indicationsurcharge = $indicationsurcharge;

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

    public function getCroquisetposition(): ?string
    {
        return $this->croquisetposition;
    }

    public function setCroquisetposition(?string $croquisetposition): static
    {
        $this->croquisetposition = $croquisetposition;

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

    public function getExcentrationcorrecte(): ?string
    {
        return $this->excentrationcorrecte;
    }

    public function setExcentrationcorrecte(?string $excentrationcorrecte): static
    {
        $this->excentrationcorrecte = $excentrationcorrecte;

        return $this;
    }
}
