<?php

namespace App\Entity;

use App\Repository\CubeRepository;
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
}