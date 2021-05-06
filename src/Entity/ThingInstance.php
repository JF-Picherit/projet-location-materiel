<?php

namespace App\Entity;

use App\Repository\ThingInstanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThingInstanceRepository::class)
 */
class ThingInstance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Thing::class, inversedBy="thingInstances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thing;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serial;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="thingInstances")
     */
    private $borrower;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="thingInstancesBooked")
     */
    private $booker;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $borrowDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $returnDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThing(): ?Thing
    {
        return $this->thing;
    }

    public function setThing(?Thing $thing): self
    {
        $this->thing = $thing;

        return $this;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getBorrower(): ?User
    {
        return $this->borrower;
    }

    public function setBorrower(?User $borrower): self
    {
        $this->borrower = $borrower;

        return $this;
    }

    public function deleteBorrower()
    {
        $this->borrower = null;

        return $this;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function deleteBooker()
    {
        $this->booker = null;

        return $this;
    }

    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(?\DateTimeInterface $borrowDate): self
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function deleteBorrowDate()
    {
        $this->borrowDate = null;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): self
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function deleteReturnDate()
    {
        $this->returnDate = null;

        return $this;
    }

    public function emptyInstance()
    {
        $this->deleteBooker();
        $this->deleteBorrower();
        $this->deleteBorrowDate();
        $this->deleteReturnDate();
        return $this;
    }
}
