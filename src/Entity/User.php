<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $discordId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Asset::class, mappedBy="author", orphanRemoval=true)
     */
    private $assets;

    /**
     * @ORM\Column(type="boolean")
     */
    private $banned;

    /**
     * @ORM\OneToMany(targetEntity=Datapack::class, mappedBy="author", orphanRemoval=true)
     */
    private $datapacks;

    public function __construct(array $data = [])
    {
        $this->username = $data['username'];
        $this->discordId = $data['id'];
        $this->avatar = $data['avatar'];
        $this->assets = new ArrayCollection();
        $this->banned = false;
        $this->datapacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // if ($this->getBanned()) {
        //     return [];
        // }

        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDiscordId(): ?int
    {
        return $this->discordId;
    }

    public function setDiscordId(int $discordId): self
    {
        $this->discordId = $discordId;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Asset[]
     */
    public function getAssets(): Collection
    {
        return $this->assets;
    }

    public function addAsset(Asset $asset): self
    {
        if (!$this->assets->contains($asset)) {
            $this->assets[] = $asset;
            $asset->setAuthor($this);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): self
    {
        if ($this->assets->contains($asset)) {
            $this->assets->removeElement($asset);
            // set the owning side to null (unless already changed)
            if ($asset->getAuthor() === $this) {
                $asset->setAuthor(null);
            }
        }

        return $this;
    }

    public function getBanned(): ?bool
    {
        return $this->banned;
    }

    public function setBanned(bool $banned): self
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * @return Collection|Datapack[]
     */
    public function getDatapacks(): Collection
    {
        return $this->datapacks;
    }

    public function addDatapack(Datapack $datapack): self
    {
        if (!$this->datapacks->contains($datapack)) {
            $this->datapacks[] = $datapack;
            $datapack->setAuthor($this);
        }

        return $this;
    }

    public function removeDatapack(Datapack $datapack): self
    {
        if ($this->datapacks->contains($datapack)) {
            $this->datapacks->removeElement($datapack);
            // set the owning side to null (unless already changed)
            if ($datapack->getAuthor() === $this) {
                $datapack->setAuthor(null);
            }
        }

        return $this;
    }
}
