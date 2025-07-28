<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'sites')]
    private ?client $client = null;

    /**
     * @var Collection<int, instrument>
     */
    #[ORM\OneToMany(targetEntity: Instrument::class, mappedBy: 'site')]
    private Collection $instrument;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function __construct()
    {
        $this->instrument = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getClient(): ?client
    {
        return $this->client;
    }

    public function setClient(?client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, instrument>
     */
    public function getInstrument(): Collection
    {
        return $this->instrument;
    }

    public function addInstrument(instrument $instrument): static
    {
        if (!$this->instrument->contains($instrument)) {
            $this->instrument->add($instrument);
            $instrument->setSite($this);
        }

        return $this;
    }

    public function removeInstrument(instrument $instrument): static
    {
        if ($this->instrument->removeElement($instrument)) {
            // set the owning side to null (unless already changed)
            if ($instrument->getSite() === $this) {
                $instrument->setSite(null);
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
