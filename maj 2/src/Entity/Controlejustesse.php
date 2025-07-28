<?php

namespace App\Entity;

use App\Repository\ControlejustesseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControlejustesseRepository::class)]
class Controlejustesse extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pointsdessai = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $valeurnominale = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $valeurmonte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $valeurdescente = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $indicationsurchage = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $ecartreleve = null;


    #[ORM\ManyToOne(inversedBy: 'controlejustesses')]
    private ?Instrument $instrument = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $justessecorrecte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sensibilitecorrecte = null;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsdessai(): ?string
    {
        return $this->pointsdessai;
    }

    public function setPointsdessai(?string $pointsdessai): static
    {
        $this->pointsdessai = $pointsdessai;

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

    public function getValeurmonte(): ?string
    {
        return $this->valeurmonte;
    }

    public function setValeurmonte(?string $valeurmonte): static
    {
        $this->valeurmonte = $valeurmonte;

        return $this;
    }

    public function getValeurdescente(): ?string
    {
        return $this->valeurdescente;
    }

    public function setValeurdescente(?string $valeurdescente): static
    {
        $this->valeurdescente = $valeurdescente;

        return $this;
    }

    public function getIndicationsurchage(): ?string
    {
        return $this->indicationsurchage;
    }

    public function setIndicationsurchage(?string $indicationsurchage): static
    {
        $this->indicationsurchage = $indicationsurchage;

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

    public function getInstrument(): ?Instrument
    {
        return $this->instrument;
    }

    public function setInstrument(?Instrument $instrument): static
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getJustessecorrecte(): ?string
    {
        return $this->justessecorrecte;
    }

    public function setJustessecorrecte(?string $justessecorrecte): static
    {
        $this->justessecorrecte = $justessecorrecte;

        return $this;
    }

    public function getSensibilitecorrecte(): ?string
    {
        return $this->sensibilitecorrecte;
    }

    public function setSensibilitecorrecte(?string $sensibilitecorrecte): static
    {
        $this->sensibilitecorrecte = $sensibilitecorrecte;

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
