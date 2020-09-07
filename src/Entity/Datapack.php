<?php

namespace App\Entity;

use App\Repository\DatapackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatapackRepository::class)
 */
class Datapack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datapackFilename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thumbnailFilename;

    /**
     * @ORM\Column(type="array")
     */
    private $pictures = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="datapacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatapackFilename(): ?string
    {
        return $this->datapackFilename;
    }

    public function setDatapackFilename(string $datapackFilename): self
    {
        $this->datapackFilename = $datapackFilename;

        return $this;
    }

    public function getThumbnailFilename(): ?string
    {
        return $this->thumbnailFilename;
    }

    public function setThumbnailFilename(string $thumbnailFilename): self
    {
        $this->thumbnailFilename = $thumbnailFilename;

        return $this;
    }

    public function getPictures(): ?array
    {
        return $this->pictures;
    }

    public function setPictures(array $pictures): self
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
