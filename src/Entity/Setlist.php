<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SetlistRepository")
 */
class Setlist
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
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $venue;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="setlists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Song", mappedBy="setlists")
     */
    private $songs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $spotify_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $setlistfm_id;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getVenue(): ?string
    {
        return $this->venue;
    }

    public function setVenue(string $venue): self
    {
        $this->venue = $venue;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Song[]
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs[] = $song;
            $song->addSetlist($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->contains($song)) {
            $this->songs->removeElement($song);
            $song->removeSetlist($this);
        }

        return $this;
    }

    public function getSpotifyId(): ?string
    {
        return $this->spotify_id;
    }

    public function setSpotifyId(string $spotify_id): self
    {
        $this->spotify_id = $spotify_id;

        return $this;
    }

    public function getSetlistfmId(): ?string
    {
        return $this->setlistfm_id;
    }

    public function setSetlistfmId(string $setlistfm_id): self
    {
        $this->setlistfm_id = $setlistfm_id;

        return $this;
    }
}
