<?php

namespace App\Entity;

use App\Repository\AssetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssetRepository::class)
 */
class Asset
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="assets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnailFilename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $assetFilename;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?user
    {
        return $this->author;
    }

    public function setAuthor(?user $author): self
    {
        $this->author = $author;

        return $this;
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getThumbnailFilename(): ?string
    {
        return $this->thumbnailFilename;
    }

    public function setThumbnailFilename(?string $thumbnailFilename): self
    {
        $this->thumbnailFilename = $thumbnailFilename;

        return $this;
    }

    public function getAssetFilename(): ?string
    {
        return $this->assetFilename;
    }

    public function setAssetFilename(string $assetFilename): self
    {
        $this->assetFilename = $assetFilename;

        return $this;
    }
}
