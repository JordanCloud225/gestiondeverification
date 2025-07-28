<?php

namespace App\Entity;

use App\Repository\ConditionsetalonnageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConditionsetalonnageRepository::class)]
class Conditionsetalonnage extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $norme = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conditionenvironnemental = null;

    /**
     * @var Collection<int, Certification>
     */
    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'conditionsetalonnage')]
    private Collection $certifications;

    #[ORM\Column(nullable: true)]
    private ?int $identreprise = null;

    public function __construct()
    {
        $this->certifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNorme(): ?string
    {
        return $this->norme;
    }

    public function setNorme(string $norme): static
    {
        $this->norme = $norme;

        return $this;
    }

    public function getConditionenvironnemental(): ?string
    {
        return $this->conditionenvironnemental;
    }

    public function setConditionenvironnemental(?string $conditionenvironnemental): static
    {
        $this->conditionenvironnemental = $conditionenvironnemental;

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
            $certification->setConditionsetalonnage($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getConditionsetalonnage() === $this) {
                $certification->setConditionsetalonnage(null);
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
