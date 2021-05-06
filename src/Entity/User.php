<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il y a déjà un compte avec cette e-mail")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity=ThingInstance::class, mappedBy="borrower", fetch="EAGER")
     */
    private $thingInstances;

    /**
     * @ORM\OneToMany(targetEntity=ThingInstance::class, mappedBy="booker", fetch="EAGER")
     */
    private $thingInstancesBooked;

    public function __construct()
    {
        $this->admin = false;
        $this->thingInstances = new ArrayCollection();
        $this->thingInstancesBooked = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        if ($this->getAdmin()) {
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

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
            $thingInstance->setBorrower($this);
        }

        return $this;
    }

    public function removeThingInstance(ThingInstance $thingInstance): self
    {
        if ($this->thingInstances->removeElement($thingInstance)) {
            // set the owning side to null (unless already changed)
            if ($thingInstance->getBorrower() === $this) {
                $thingInstance->setBorrower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ThingInstance[]
     */
    public function getThingInstancesBooked(): Collection
    {
        return $this->thingInstancesBooked;
    }

    public function addThingInstancesBooked(ThingInstance $thingInstancesBooked): self
    {
        if (!$this->thingInstancesBooked->contains($thingInstancesBooked)) {
            $this->thingInstancesBooked[] = $thingInstancesBooked;
            $thingInstancesBooked->setBooker($this);
        }

        return $this;
    }

    public function removeThingInstancesBooked(ThingInstance $thingInstancesBooked): self
    {
        if ($this->thingInstancesBooked->removeElement($thingInstancesBooked)) {
            // set the owning side to null (unless already changed)
            if ($thingInstancesBooked->getBooker() === $this) {
                $thingInstancesBooked->setBooker(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getEmail();
    }
}
