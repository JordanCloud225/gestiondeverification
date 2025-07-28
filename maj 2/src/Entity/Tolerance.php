<?php

namespace App\Entity;

use App\Repository\ToleranceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ToleranceRepository::class)]
class Tolerance extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)] 
    private ?int $valeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unitemesure = null;

    /**
     * @var Collection<int, Instrument>
     */
    #[ORM\OneToMany(targetEntity: Instrument::class, mappedBy: 'tolerance')]
    private Collection $instruments;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function __construct()
    {
        $this->instruments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getUnitemesure(): ?string
    {
        return $this->unitemesure;
    }

    public function setUnitemesure(string $unitemesure): static
    {
        $this->unitemesure = $unitemesure;

        return $this;
    }

    /**
     * @return Collection<int, Instrument>
     */
    public function getInstruments(): Collection
    {
        return $this->instruments;
    }

    public function addInstrument(Instrument $instrument): static
    {
        if (!$this->instruments->contains($instrument)) {
            $this->instruments->add($instrument);
            $instrument->setTolerance($this);
        }

        return $this;
    }

    public function removeInstrument(Instrument $instrument): static
    {
        if ($this->instruments->removeElement($instrument)) {
            // set the owning side to null (unless already changed)
            if ($instrument->getTolerance() === $this) {
                $instrument->setTolerance(null);
            }
        }

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
