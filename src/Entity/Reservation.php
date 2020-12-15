<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start_from;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_to;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="reservations", cascade={"persist", "remove"})
     */
    private $ad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservations")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartFrom(): ?\DateTimeInterface
    {
        return $this->start_from;
    }

    public function setStartFrom(?\DateTimeInterface $start_from): self
    {
        $this->start_from = $start_from;

        return $this;
    }

    public function getEndTo(): ?\DateTimeInterface
    {
        return $this->end_to;
    }

    public function setEndTo(?\DateTimeInterface $end_to): self
    {
        $this->end_to = $end_to;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

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
}