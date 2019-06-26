<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SongRepository")
 */
class Song
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $album;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Setlist", inversedBy="songs")
     */
    private $setlists;

    public function __construct()
    {
        $this->setlists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(string $album): self
    {
        $this->album = $album;

        return $this;
    }

    /**
     * @return Collection|Setlist[]
     */
    public function getSetlists(): Collection
    {
        return $this->setlists;
    }

    public function addSetlist(Setlist $setlist): self
    {
        if (!$this->setlists->contains($setlist)) {
            $this->setlists[] = $setlist;
        }

        return $this;
    }

    public function removeSetlist(Setlist $setlist): self
    {
        if ($this->setlists->contains($setlist)) {
            $this->setlists->removeElement($setlist);
        }

        return $this;
    }
}
