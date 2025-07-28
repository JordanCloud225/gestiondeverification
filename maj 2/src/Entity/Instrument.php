<?php

namespace App\Entity;

use App\Repository\InstrumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstrumentRepository::class)]
class Instrument extends EntityBase 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroserie = null; 

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $porteemax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $porteemini = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $classeprecision = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $echelonverification = null;

    

    #[ORM\ManyToOne(inversedBy: 'instruments')]
    private ?Tolerance $tolerance = null;

    /**
     * @var Collection<int, Controlefidelite>
     */
    #[ORM\OneToMany(targetEntity: Controlefidelite::class, mappedBy: 'instrument', cascade: ['persist', 'remove'])]
    private Collection $controlefidelites;

    /**
     * @var Collection<int, Controlejustesse>
     */
    #[ORM\OneToMany(targetEntity: Controlejustesse::class, mappedBy: 'instrument', cascade: ['persist', 'remove'])]
    private Collection $controlejustesses;

    /**
     * @var Collection<int, Controledexcentration>
     */
    #[ORM\OneToMany(targetEntity: Controledexcentration::class, mappedBy: 'instrument', cascade: ['persist', 'remove'])]
    private Collection $controledexcentrations;

    #[ORM\Column(nullable: true)]
    private ?bool $etat = null;

    #[ORM\ManyToOne(inversedBy: 'instrument')]
    private ?Client $client = null;

    /**
     * @var Collection<int, Certification>
     */
    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'instrument')]
    private Collection $certifications;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeinstrument = null;

    #[ORM\ManyToOne(inversedBy: 'instruments')]
    private ?Designation $designation = null;

    #[ORM\ManyToOne(inversedBy: 'instruments')]
    private ?Marque $marque = null;

    #[ORM\ManyToOne(inversedBy: 'instruments')]
    private ?Modele $modele = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numintervention = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $demandetravaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detailtravaux = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $observationclient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $equipement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signaturedate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $signataire = null;

    #[ORM\ManyToOne(inversedBy: 'instrument')]
    private ?Technicien $technicien = null;

    #[ORM\ManyToOne(inversedBy: 'instrument')]
    private ?Site $site = null;

    #[ORM\ManyToOne(inversedBy: 'instrument')]
    private ?Typeinstrument $typeinstrument = null;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    /**
     * @var Collection<int, Designation>
     */
    

    public function __construct()
    {
        $this->controlefidelites = new ArrayCollection();
        $this->controlejustesses = new ArrayCollection();
        $this->controledexcentrations = new ArrayCollection();
        $this->certifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroserie(): ?string
    {
        return $this->numeroserie;
    }

    public function setNumeroserie(?string $numeroserie): static
    {
        $this->numeroserie = $numeroserie;

        return $this;
    }

    public function getPorteemax(): ?string
    {
        return $this->porteemax;
    }

    public function setPorteemax(string $porteemax): static
    {
        $this->porteemax = $porteemax;

        return $this;
    }

    public function getPorteemini(): ?string
    {
        return $this->porteemini;
    }

    public function setPorteemini(string $porteemini): static
    {
        $this->porteemini = $porteemini;

        return $this;
    }

    public function getClasseprecision(): ?string
    {
        return $this->classeprecision;
    }

    public function setClasseprecision(string $classeprecision): static
    {
        $this->classeprecision = $classeprecision;

        return $this;
    }

    public function getEchelonverification(): ?string
    {
        return $this->echelonverification;
    }

    public function setEchelonverification(string $echelonverification): static
    {
        $this->echelonverification = $echelonverification;

        return $this;
    }

   

    public function getTolerance(): ?Tolerance
    {
        return $this->tolerance;
    }

    public function setTolerance(?Tolerance $tolerance): static
    {
        $this->tolerance = $tolerance;

        return $this;
    }

    /**
     * @return Collection<int, Controlefidelite>
     */
    public function getControlefidelites(): Collection
    {
        return $this->controlefidelites;
    }

    public function addControlefidelite(Controlefidelite $controlefidelite): static
    {
        if (!$this->controlefidelites->contains($controlefidelite)) {
            $this->controlefidelites->add($controlefidelite);
            $controlefidelite->setInstrument($this);
        }

        return $this;
    }

    public function removeControlefidelite(Controlefidelite $controlefidelite): static
    {
        if ($this->controlefidelites->removeElement($controlefidelite)) {
            // set the owning side to null (unless already changed)
            if ($controlefidelite->getInstrument() === $this) {
                $controlefidelite->setInstrument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Controlejustesse>
     */
    public function getControlejustesses(): Collection
    {
        return $this->controlejustesses;
    }

    public function addControlejustess(Controlejustesse $controlejustess): static
    {
        if (!$this->controlejustesses->contains($controlejustess)) {
            $this->controlejustesses->add($controlejustess);
            $controlejustess->setInstrument($this);
        }

        return $this;
    }

    public function removeControlejustess(Controlejustesse $controlejustess): static
    {
        if ($this->controlejustesses->removeElement($controlejustess)) {
            // set the owning side to null (unless already changed)
            if ($controlejustess->getInstrument() === $this) {
                $controlejustess->setInstrument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Controledexcentration>
     */
    public function getControledexcentrations(): Collection
    {
        return $this->controledexcentrations;
    }

    public function addControledexcentration(Controledexcentration $controledexcentration): static
    {
        if (!$this->controledexcentrations->contains($controledexcentration)) {
            $this->controledexcentrations->add($controledexcentration);
            $controledexcentration->setInstrument($this);
        }

        return $this;
    }

    public function removeControledexcentration(Controledexcentration $controledexcentration): static
    {
        if ($this->controledexcentrations->removeElement($controledexcentration)) {
            // set the owning side to null (unless already changed)
            if ($controledexcentration->getInstrument() === $this) {
                $controledexcentration->setInstrument(null);
            }
        }

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): static
    {
        $this->etat = $etat;

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

    /**
     * @return Collection<int, Certification>
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setInstrument($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getInstrument() === $this) {
                $certification->setInstrument(null);
            }
        }

        return $this;
    }

    public function getCodeinstrument(): ?string
    {
        return $this->codeinstrument;
    }

    public function setCodeinstrument(?string $codeinstrument): static
    {
        $this->codeinstrument = $codeinstrument;

        return $this;
    }

    public function getDesignation(): ?Designation
    {
        return $this->designation;
    }

    public function setDesignation(?Designation $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getNumintervention(): ?string
    {
        return $this->numintervention;
    }

    public function setNumintervention(?string $numintervention): static
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

    public function getObservationclient(): ?string
    {
        return $this->observationclient;
    }

    public function setObservationclient(string $observationclient): static
    {
        $this->observationclient = $observationclient;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(?string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getEquipement(): ?string
    {
        return $this->equipement;
    }

    public function setEquipement(?string $equipement): static
    {
        $this->equipement = $equipement;

        return $this;
    }

    public function getSignaturedate(): ?string
    {
        return $this->signaturedate;
    }

    public function setSignaturedate(?string $signaturedate): static
    {
        $this->signaturedate = $signaturedate;

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

    public function getTechnicien(): ?Technicien
    {
        return $this->technicien;
    }

    public function setTechnicien(?Technicien $technicien): static
    {
        $this->technicien = $technicien;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getTypeinstrument(): ?Typeinstrument
    {
        return $this->typeinstrument;
    }

    public function setTypeinstrument(?Typeinstrument $typeinstrument): static
    {
        $this->typeinstrument = $typeinstrument;

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
