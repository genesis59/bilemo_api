<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups('read:smartphone')]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:smartphone')]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    #[Groups('read:smartphone')]
    private ?string $hex = null;

    #[ORM\ManyToMany(targetEntity: Smartphone::class, mappedBy: 'colors')]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHex(): ?string
    {
        return $this->hex;
    }

    public function setHex(string $hex): self
    {
        $this->hex = $hex;

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
            $smartphone->addColor($this);
        }

        return $this;
    }

    public function removeSmartphone(Smartphone $smartphone): self
    {
        if ($this->smartphones->removeElement($smartphone)) {
            $smartphone->removeColor($this);
        }

        return $this;
    }
}
