<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Articles::class, mappedBy="categories")
     */
    private $artciles;

    public function __construct()
    {
        $this->artciles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection|Artciles[]
     */
    public function getArtciles(): Collection
    {
        return $this->artciles;
    }

    public function addArtcile(Artciles $artcile): self
    {
        if (!$this->artciles->contains($artcile)) {
            $this->artciles[] = $artcile;
            $artcile->addCategory($this);
        }

        return $this;
    }

    public function removeArtcile(Artciles $artcile): self
    {
        if ($this->artciles->contains($artcile)) {
            $this->artciles->removeElement($artcile);
            $artcile->removeCategory($this);
        }

        return $this;
    }
}
