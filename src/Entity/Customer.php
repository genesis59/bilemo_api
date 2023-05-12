<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[UniqueEntity(
    fields: ['email'],
    message: 'app.constraint.customer.email.unique',
    repositoryMethod: 'getSimilarEmailForReseller'
)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups(['read:customer'])]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:customer'])]
    #[Assert\NotBlank(message: 'app.constraint.customer.first_name.not_blank')]
    #[Assert\Length(
        min:1,
        max:255,
        minMessage:'app.constraint.customer.first_name.length_min_message',
        maxMessage: 'app.constraint.customer.first_name.length_max_message'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-]+$/i',
        message: 'app.constraint.customer.first_name.regex'
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:customer'])]
    #[Assert\NotBlank(message: 'app.constraint.customer.last_name.not_blank')]
    #[Assert\Length(
        min: 1,
        max:255,
        minMessage: 'app.constraint.customer.last_name.length_min_message',
        maxMessage: 'app.constraint.customer.last_name.length_max_message'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-]+$/i',
        message: 'app.constraint.customer.last_name.regex'
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'app.constraint.customer.email.not_blank')]
    #[Assert\Email(message: 'app.constraint.customer.email.email')]
    #[Assert\Length(max:255, maxMessage: 'app.constraint.customer.email.length_max_message')]
    #[Groups(['read:customer'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:customer'])]
    #[Assert\NotBlank(message: 'app.constraint.customer.phone_number.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'app.constraint.customer.phone_number.length_min_message',
        maxMessage: 'app.constraint.customer.phone_number.length_max_message'
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:customer'])]
    #[Assert\NotBlank(message: 'app.constraint.customer.street.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'app.constraint.customer.street.length_min_message',
        maxMessage: 'app.constraint.customer.street.length_max_message'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-,]+$/i',
        message: 'app.constraint.customer.last_name.regex'
    )]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:customer'])]
    #[Assert\NotBlank(message: 'app.constraint.customer.city.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'app.constraint.customer.city.length_min_message',
        maxMessage: 'app.constraint.customer.city.length_max_message'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-,]+$/i',
        message: 'app.constraint.customer.last_name.regex'
    )]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:customer'])]
    #[Assert\NotBlank(message: 'app.constraint.customer.country.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'app.constraint.customer.country.length_min_message',
        maxMessage: 'app.constraint.customer.country.length_max_message'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s\'-,]+$/i',
        message: 'app.constraint.customer.last_name.regex'
    )]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:customer'])]
    #[Assert\NotBlank(message: 'app.constraint.customer.postcode.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'app.constraint.customer.postcode.length_min_message',
        maxMessage: 'app.constraint.customer.postcode.length_max_message'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9-]+$/i',
        message: 'app.constraint.customer.last_name.regex'
    )]
    private ?string $postCode = null;

    #[ORM\Column]
    #[Groups(['read:customer'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:customer'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    private Reseller $reseller;

    #[ORM\ManyToMany(targetEntity: Smartphone::class, inversedBy: 'customers')]
//    #[Groups(['read:customer'])]
    private Collection $smartphones;

    public function __construct()
    {
        $this->smartphones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getReseller(): ?Reseller
    {
        return $this->reseller;
    }


    public function setReseller(?Reseller $reseller): self
    {
        $this->reseller = $reseller;

        return $this;
    }

    /**
     * @return Collection<int, Smartphone>
     */
    public function getSmartphones(): Collection
    {
        return $this->smartphones;
    }

    public function addSmartphone(Smartphone $smartphone): self
    {
        if (!$this->smartphones->contains($smartphone)) {
            $this->smartphones->add($smartphone);
        }

        return $this;
    }

    public function removeSmartphone(Smartphone $smartphone): self
    {
        $this->smartphones->removeElement($smartphone);

        return $this;
    }
}
