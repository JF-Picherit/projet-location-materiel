<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Thing::class, mappedBy="category", orphanRemoval=true)
     */
    private $things;

    public function __construct()
    {
        $this->things = new ArrayCollection();
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

    /**
     * @return Collection|Thing[]
     */
    public function getThings(): Collection
    {
        return $this->things;
    }

    public function addThing(Thing $thing): self
    {
        if (!$this->things->contains($thing)) {
            $this->things[] = $thing;
            $thing->setCategory($this);
        }

        return $this;
    }

    public function removeThing(Thing $thing): self
    {
        if ($this->things->removeElement($thing)) {
            // set the owning side to null (unless already changed)
            if ($thing->getCategory() === $this) {
                $thing->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
