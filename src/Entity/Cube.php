<?php

namespace App\Entity;

use App\Repository\CubeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[ORM\Entity(repositoryClass: CubeRepository::class)]
#[Vich\Uploadable]
class Cube
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $description = null;
    
    
    #[Vich\UploadableField(mapping: 'cube_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;
    
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
    
    
    #[ORM\ManyToOne(inversedBy: 'cubes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CubeCollection $cubeCollection = null;

    /**
     * @var Collection<int, CubeGallery>
     */
    #[ORM\ManyToMany(targetEntity: CubeGallery::class, mappedBy: 'cubes')]
    private Collection $cubeGalleries;

    public function __construct()
    {
        $this->cubeGalleries = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    public function setDescription(string $description): static
    {
        $this->description = $description;
        
        return $this;
    }
    
    public function getCubeCollection(): ?CubeCollection
    {
        return $this->cubeCollection;
    }
    
    public function setCubeCollection(?CubeCollection $cubeCollection): static
    {
        $this->cubeCollection = $cubeCollection;
        
        return $this;
    }
    
    public function __toString(): string
    {
        return 'Cube #' . $this->id . ' : ' . $this->description;
    }

    /**
     * @return Collection<int, CubeGallery>
     */
    public function getCubeGalleries(): Collection
    {
        return $this->cubeGalleries;
    }

    public function addCubeGallery(CubeGallery $cubeGallery): static
    {
        if (!$this->cubeGalleries->contains($cubeGallery)) {
            $this->cubeGalleries->add($cubeGallery);
            $cubeGallery->addCube($this);
        }

        return $this;
    }

    public function removeCubeGallery(CubeGallery $cubeGallery): static
    {
        if ($this->cubeGalleries->removeElement($cubeGallery)) {
            $cubeGallery->removeCube($this);
        }

        return $this;
    }
    
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    
    public function setImageFile(?File $imageFile = null): static
    {
        $this->imageFile = $imageFile;
        
        if ($imageFile !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        
        return $this;
    }
    
    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    
    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;
        
        return $this;
    }
    
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
}