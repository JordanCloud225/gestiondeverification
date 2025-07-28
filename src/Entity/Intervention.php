<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $numintervention = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $demandetravaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detailtravaux = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateintervention = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $heure = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteequipement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $observationclient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $interlocuteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $site = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactinterlocuteur = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?Typeinstrument $typedequipement = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?Marque $marque = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?Modele $modele = null;

    #[ORM\Column(nullable: true)]
    private ?int $porteemaxi = null;

    #[ORM\Column(nullable: true)]
    private ?int $porteemini = null;

    #[ORM\Column(nullable: true)]
    private ?int $echelonunite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numserie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $equipement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signature = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signataire = null;

    /**
     * @var Collection<int, Presence>
     */
    #[ORM\OneToMany(targetEntity: Presence::class, mappedBy: 'intervention')]
    private Collection $presences;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function __construct()
    {
        $this->presences = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumintervention(): ?int
    {
        return $this->numintervention;
    }

    public function setNumintervention(?int $numintervention): static
    {
        $this->numintervention = $numintervention;

        return $this;
    }

    public function getDemandetravaux(): ?string
    {
        return $this->demandetravaux;
    }

    public function setDemandetravaux(?string $demandetravaux): static
    {
        $this->demandetravaux = $demandetravaux;

        return $this;
    }

    public function getDetailtravaux(): ?string
    {
        return $this->detailtravaux;
    }

    public function setDetailtravaux(?string $detailtravaux): static
    {
        $this->detailtravaux = $detailtravaux;

        return $this;
    }

    public function getDateintervention(): ?\DateTime
    {
        return $this->dateintervention;
    }

    public function setDateintervention(?\DateTime $dateintervention): static
    {
        $this->dateintervention = $dateintervention;

        return $this;
    }

    public function getHeure(): ?\DateTime
    {
        return $this->heure;
    }

    public function setHeure(?\DateTime $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getQuantiteequipement(): ?int
    {
        return $this->quantiteequipement;
    }

    public function setQuantiteequipement(?int $quantiteequipement): static
    {
        $this->quantiteequipement = $quantiteequipement;

        return $this;
    }

    public function getObservationclient(): ?string
    {
        return $this->observationclient;
    }

    public function setObservationclient(?string $observationclient): static
    {
        $this->observationclient = $observationclient;

        return $this;
    }

    public function getInterlocuteur(): ?string
    {
        return $this->interlocuteur;
    }

    public function setInterlocuteur(?string $interlocuteur): static
    {
        $this->interlocuteur = $interlocuteur;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getContactinterlocuteur(): ?string
    {
        return $this->contactinterlocuteur;
    }

    public function setContactinterlocuteur(?string $contactinterlocuteur): static
    {
        $this->contactinterlocuteur = $contactinterlocuteur;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getTypedequipement(): ?Typeinstrument
    {
        return $this->typedequipement;
    }

    public function setTypedequipement(?Typeinstrument $typedequipement): static
    {
        $this->typedequipement = $typedequipement;

        return $this;
    }

    public function getMarque(): ?marque
    {
        return $this->marque;
    }

    public function setMarque(?marque $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?modele
    {
        return $this->modele;
    }

    public function setModele(?modele $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getPorteemaxi(): ?int
    {
        return $this->porteemaxi;
    }

    public function setPorteemaxi(?int $porteemaxi): static
    {
        $this->porteemaxi = $porteemaxi;

        return $this;
    }

    public function getPorteemini(): ?int
    {
        return $this->porteemini;
    }

    public function setPorteemini(?int $porteemini): static
    {
        $this->porteemini = $porteemini;

        return $this;
    }

    public function getEchelonunite(): ?int
    {
        return $this->echelonunite;
    }

    public function setEchelonunite(?int $echelonunite): static
    {
        $this->echelonunite = $echelonunite;

        return $this;
    }

    public function getNumserie(): ?string
    {
        return $this->numserie;
    }

    public function setNumserie(?string $numserie): static
    {
        $this->numserie = $numserie;

        return $this;
    }

    public function setEquipement(?string $equipement): static
    {
        $this->equipement = $equipement;

        return $this;
    }
    public function getEquipement(): ?string
    {
        return $this->equipement;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): static
    {
        $this->signature = $signature;

        return $this;
    }

    public function getSignataire(): ?string
    {
        return $this->signataire;
    }

    public function setSignataire(?string $signataire): static
    {
        $this->signataire = $signataire;

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
            $presence->setIntervention($this);
        }

        return $this;
    }

    public function removePresence(Presence $presence): static
    {
        if ($this->presences->removeElement($presence)) {
            // set the owning side to null (unless already changed)
            if ($presence->getIntervention() === $this) {
                $presence->setIntervention(null);
            }
        }

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
