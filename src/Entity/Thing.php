<?php

namespace App\Entity;

use App\Repository\ThingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThingRepository::class)
 */
class Thing
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
     * @ORM\OneToMany(targetEntity=ThingInstance::class, mappedBy="thing", orphanRemoval=true)
     */
    private $thingInstances;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="things")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->thingInstances = new ArrayCollection();
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
     * @return Collection|ThingInstance[]
     */
    public function getThingInstances(): Collection
    {
        return $this->thingInstances;
    }

    public function addThingInstance(ThingInstance $thingInstance): self
    {
        if (!$this->thingInstances->contains($thingInstance)) {
            $this->thingInstances[] = $thingInstance;
            $thingInstance->setThing($this);
        }

        return $this;
    }

    public function removeThingInstance(ThingInstance $thingInstance): self
    {
        if ($this->thingInstances->removeElement($thingInstance)) {
            // set the owning side to null (unless already changed)
            if ($thingInstance->getThing() === $this) {
                $thingInstance->setThing(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAvailable() 
    {
        $nbThingAvailable = count($this->getThingInstances());
        foreach($this->getThingInstances() as $instance){
            if($instance->getBooker() || $instance->getBorrower()){
                $nbThingAvailable--;
            }
        }
        
        return $nbThingAvailable;
    }

    public function getInstanceBookers()
    {
        $bookers = array();
        foreach($this->getThingInstances() as $instance){
            if($instance->getBooker() && !$instance->getBorrower()) {
                $bookers[] = $instance->getBooker()->getId();
            }
        }
        return $bookers;
    }

    public function getInfoBorrowers()
    {
        $borrowersInfo = array();
        foreach($this->getThingInstances() as $instance){
            if($instance->getBorrower()) {
                $idBorrower = $instance->getBorrower()->getId();
                $borrowDate = $instance->getBorrowDate();
                $returnDate = $instance->getReturnDate();

                $borrowersInfo[$idBorrower] = array(
                    "borrow_date" => $borrowDate,
                    "return_date" => $returnDate
                );
            }
        }

        return $borrowersInfo;
    }
}
