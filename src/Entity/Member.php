<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(inversedBy: 'member', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?CubeCollection $cubeCollection = null;

    /**
     * @var Collection<int, CubeGallery>
     */
    #[ORM\OneToMany(targetEntity: CubeGallery::class, mappedBy: 'member')]
    private Collection $cubeGalleries;
    
    /**
     * @var Collection<int, Cube>
     */
    #[ORM\ManyToMany(targetEntity: Cube::class)]
    #[ORM\JoinTable(name: 'member_favorite_cube')]
    private Collection $favoriteCubes;

    public function __construct()
    {
        $this->cubeGalleries = new ArrayCollection();
        $this->favoriteCubes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getCubeCollection(): ?CubeCollection
    {
        return $this->cubeCollection;
    }

    public function setCubeCollection(CubeCollection $cubeCollection): static
    {
        $this->cubeCollection = $cubeCollection;

        return $this;
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
            $cubeGallery->setMember($this);
        }

        return $this;
    }

    public function removeCubeGallery(CubeGallery $cubeGallery): static
    {
        if ($this->cubeGalleries->removeElement($cubeGallery)) {
            // set the owning side to null (unless already changed)
            if ($cubeGallery->getMember() === $this) {
                $cubeGallery->setMember(null);
            }
        }

        return $this;
    }
    
    
    /**
     * @return Collection<int, Cube>
     */
    public function getFavoriteCubes(): Collection
    {
        return $this->favoriteCubes;
    }
    
    public function addFavoriteCube(Cube $cube): static
    {
        if (!$this->favoriteCubes->contains($cube)) {
            $this->favoriteCubes->add($cube);
        }
        
        return $this;
    }
    
    public function removeFavoriteCube(Cube $cube): static
    {
        $this->favoriteCubes->removeElement($cube);
        
        return $this;
    }
    
}
