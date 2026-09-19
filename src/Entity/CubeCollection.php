<?php

namespace App\Entity;

use App\Repository\CubeCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CubeCollectionRepository::class)]
class CubeCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    /**
     * @var Collection<int, Cube>
     */
    #[ORM\OneToMany(targetEntity: Cube::class, mappedBy: 'cubeCollection', cascade: ['persist'])]
    private Collection $cubes;
    
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToOne(mappedBy: 'cubeCollection', cascade: ['persist', 'remove'])]
    private ?Member $member = null;
    
    public function __construct()
    {
        $this->cubes = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return Collection<int, Cube>
     */
    public function getCubes(): Collection
    {
        return $this->cubes;
    }
    
    public function addCube(Cube $cube): static
    {
        if (!$this->cubes->contains($cube)) {
            $this->cubes->add($cube);
            $cube->setCubeCollection($this);
        }
        
        return $this;
    }
    
    public function removeCube(Cube $cube): static
    {
        if ($this->cubes->removeElement($cube)) {
            if ($cube->getCubeCollection() === $this) {
                $cube->setCubeCollection(null);
            }
        }
        
        return $this;
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

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(Member $member): static
    {
        // set the owning side of the relation if necessary
        if ($member->getCubeCollection() !== $this) {
            $member->setCubeCollection($this);
        }

        $this->member = $member;

        return $this;
    }
}