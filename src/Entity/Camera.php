<?php

namespace App\Entity;

use App\Repository\CameraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CameraRepository::class)]
class Camera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups('read:smartphone')]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:smartphone')]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $numericZoom = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $resolution = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?bool $numericZoomBack = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?bool $flash = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?bool $flashBack = null;

    #[ORM\ManyToMany(targetEntity: Smartphone::class, mappedBy: 'cameras')]
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

    public function getNumericZoom(): ?int
    {
        return $this->numericZoom;
    }

    public function setNumericZoom(int $numericZoom): self
    {
        $this->numericZoom = $numericZoom;

        return $this;
    }

    public function getResolution(): ?int
    {
        return $this->resolution;
    }

    public function setResolution(int $resolution): self
    {
        $this->resolution = $resolution;

        return $this;
    }

    public function isNumericZoomBack(): ?bool
    {
        return $this->numericZoomBack;
    }

    public function setNumericZoomBack(bool $numericZoomBack): self
    {
        $this->numericZoomBack = $numericZoomBack;

        return $this;
    }

    public function isFlash(): ?bool
    {
        return $this->flash;
    }

    public function setFlash(bool $flash): self
    {
        $this->flash = $flash;

        return $this;
    }

    public function isFlashBack(): ?bool
    {
        return $this->flashBack;
    }

    public function setFlashBack(bool $flashBack): self
    {
        $this->flashBack = $flashBack;

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
            $smartphone->addCamera($this);
        }

        return $this;
    }

    public function removeSmartphone(Smartphone $smartphone): self
    {
        if ($this->smartphones->removeElement($smartphone)) {
            $smartphone->removeCamera($this);
        }

        return $this;
    }
}
