<?php

namespace App\Entity;

use App\Repository\TechnicienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnicienRepository::class)]
class Technicien extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nometprenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    /**
     * @var Collection<int, instrument>
     */
    #[ORM\OneToMany(targetEntity: Instrument::class, mappedBy: 'technicien')]
    private Collection $instrument;

    /**
     * @var Collection<int, Intervention>
     */
    #[ORM\ManyToMany(targetEntity: Intervention::class, mappedBy: 'technicien')]
    private Collection $interventions;

    /**
     * @var Collection<int, Presence>
     */
    #[ORM\OneToMany(targetEntity: Presence::class, mappedBy: 'technicien')]
    private Collection $presences;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function __construct()
    {
        $this->instrument = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->presences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNometprenom(): ?string
    {
        return $this->nometprenom;
    }

    public function setNometprenom(?string $nometprenom): static
    {
        $this->nometprenom = $nometprenom;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

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
            $instrument->setTechnicien($this);
        }

        return $this;
    }

    public function removeInstrument(instrument $instrument): static
    {
        if ($this->instrument->removeElement($instrument)) {
            // set the owning side to null (unless already changed)
            if ($instrument->getTechnicien() === $this) {
                $instrument->setTechnicien(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->addTechnicien($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            $intervention->removeTechnicien($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Presence>
     */
    public function getPresences(): Collection
    {
        return $this->presences;
    }

    public function addPresence(Presence $presence): static
    {
        if (!$this->presences->contains($presence)) {
            $this->presences->add($presence);
            $presence->setTechnicien($this);
        }

        return $this;
    }

    public function removePresence(Presence $presence): static
    {
        if ($this->presences->removeElement($presence)) {
            // set the owning side to null (unless already changed)
            if ($presence->getTechnicien() === $this) {
                $presence->setTechnicien(null);
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
