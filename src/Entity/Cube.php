<?php

namespace App\Entity;

use App\Repository\CubeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CubeRepository::class)]
class Cube
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $description = null;
    
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
}