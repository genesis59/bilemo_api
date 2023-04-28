<?php

namespace App\Entity;

use App\Repository\ResellerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ResellerRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'app.constraint.reseller.email.unique')]
#[UniqueEntity(fields: ['company'], message: 'app.constraint.reseller.company.unique')]
class Reseller implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'app.constraint.reseller.email.not_blank')]
    #[Assert\Email(message: 'app.constraint.reseller.email.email')]
    #[Assert\Length(max:180, maxMessage: 'app.constraint.reseller.email.length_max_message')]
    #[Groups(['read:reseller'])]
    private ?string $email = null;

    /**
     * @var array<string>
     */
    #[ORM\Column]
    #[Groups(['read:reseller'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'app.constraint.reseller.password.not_blank')]
    #[Assert\NotCompromisedPassword(message: 'app.constraint.reseller.password.not_compromised_password')]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-.,?;:§+!*$@%_])([-.,?;:§+!*$@%_\w]{8,255})$/',
        message: 'app.constraint.reseller.password.regex'
    )]
    private ?string $password = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups(['read:reseller'])]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'app.constraint.reseller.company.not_blank')]
    #[Groups(['read:reseller'])]
    private ?string $company = null;

    #[ORM\Column]
    #[Groups(['read:reseller'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Customer::class, mappedBy: 'resellers')]
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
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
    public function getUserIdentifier(): string
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

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->addReseller($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            $customer->removeReseller($this);
        }

        return $this;
    }
}
