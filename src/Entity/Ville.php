<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 */
class Ville
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
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="ville")
     */
    private $name;

    
    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function __toString() {
        if(is_null($this->title)) {
            return 'NULL';
        }
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(Ad $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name[] = $name;
            $name->setVille($this);
        }

        return $this;
    }

    public function removeName(Ad $name): self
    {
        if ($this->name->contains($name)) {
            $this->name->removeElement($name);
            // set the owning side to null (unless already changed)
            if ($name->getVille() === $this) {
                $name->setVille(null);
            }
        }

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

   
}
