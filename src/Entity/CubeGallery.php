<?php

namespace App\Entity;

use App\Repository\CubeGalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CubeGalleryRepository::class)]
class CubeGallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $publiee = null;

    #[ORM\ManyToOne(inversedBy: 'cubeGalleries')]
    private ?Member $member = null;

    /**
     * @var Collection<int, Cube>
     */
    #[ORM\ManyToMany(targetEntity: Cube::class, inversedBy: 'cubeGalleries')]
    private Collection $cubes;

    public function __construct()
    {
        $this->cubes = new ArrayCollection();
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

    public function isPubliee(): ?bool
    {
        return $this->publiee;
    }

    public function setPubliee(bool $publiee): static
    {
        $this->publiee = $publiee;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

        return $this;
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
        }

        return $this;
    }

    public function removeCube(Cube $cube): static
    {
        $this->cubes->removeElement($cube);

        return $this;
    }
}
