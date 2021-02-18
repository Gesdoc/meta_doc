<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("slug")
 */

class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    private $label;

    /**
     * @ORM\Column(type="date")
     */
    #[Assert\NotBlank]
    private $start_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $end_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    private $uri;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $version;

    /**
     * @ORM\ManyToMany(targetEntity=Metadata::class, inversedBy="documents")
     */
    private $metadatas;

    /**
     * @ORM\OneToMany(targetEntity=DocumentDocument::class, mappedBy="document_source")
     */
    private $documentSources;

    /**
     * @ORM\OneToMany(targetEntity=DocumentDocument::class, mappedBy="document_target")
     */
    private $documentTargets;

    /**
     * @ORM\ManyToOne(targetEntity=Classification::class, inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Assert\NotBlank]
    private $classification;

    /**
     * @ORM\Column(type="string", length=500, unique=true)
     */
    private $slug;



    public function __construct()
    {
        $this->metadatas = new ArrayCollection();
        $this->documentSources = new ArrayCollection();
        $this->documentTargets = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label.'.'.$this->start_date->format('Y-m-d').'.'.$this->version;
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return Collection|Metadata[]
     */
    public function getMetadatas(): Collection
    {
        return $this->metadatas;
    }

    public function addMetadata(Metadata $metadata): self
    {
        if (!$this->metadatas->contains($metadata)) {
            $this->metadatas[] = $metadata;
        }

        return $this;
    }

    public function removeMetadata(Metadata $metadata): self
    {
        $this->metadatas->removeElement($metadata);

        return $this;
    }

    /**
     * @return Collection|DocumentDocument[]
     */
    public function getDocumentSources(): Collection
    {
        return $this->documentSources;
    }

    public function addDocumentSource(DocumentDocument $documentSource): self
    {
        if (!$this->documentSources->contains($documentSource)) {
            $this->documentSources[] = $documentSource;
            $documentSource->setDocumentSource($this);
        }

        return $this;
    }

    public function removeDocumentSource(DocumentDocument $documentSource): self
    {
        if ($this->documentSources->removeElement($documentSource)) {
            // set the owning side to null (unless already changed)
            if ($documentSource->getDocumentSource() === $this) {
                $documentSource->setDocumentSource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DocumentDocument[]
     */
    public function getDocumentTargets(): Collection
    {
        return $this->documentTargets;
    }

    public function addDocumentTarget(DocumentDocument $documentTarget): self
    {
        if (!$this->documentTargets->contains($documentTarget)) {
            $this->documentTargets[] = $documentTarget;
            $documentTarget->setDocumentTarget($this);
        }

        return $this;
    }

    public function removeDocumentTarget(DocumentDocument $documentTarget): self
    {
        if ($this->documentTargets->removeElement($documentTarget)) {
            // set the owning side to null (unless already changed)
            if ($documentTarget->getDocumentTarget() === $this) {
                $documentTarget->setDocumentTarget(null);
            }
        }

        return $this;
    }

    public function getClassification(): ?Classification
    {
        return $this->classification;
    }

    public function setClassification(?Classification $classification): self
    {
        $this->classification = $classification;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
    * @ORM\PrePersist
    */
    public function setStartDateValue()
    {
        $this->start_date = new \DateTime();
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }
    }
    

}
