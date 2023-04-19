<?php

namespace App\Entity;

use App\Repository\ScreenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ScreenRepository::class)]
class Screen
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
    private ?string $resolutionMainScreen = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $diagonal = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?bool $touchScreen = null;

    #[ORM\OneToMany(mappedBy: 'screen', targetEntity: Smartphone::class)]
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

    public function getResolutionMainScreen(): ?string
    {
        return $this->resolutionMainScreen;
    }

    public function setResolutionMainScreen(string $resolutionMainScreen): self
    {
        $this->resolutionMainScreen = $resolutionMainScreen;

        return $this;
    }

    public function getDiagonal(): ?int
    {
        return $this->diagonal;
    }

    public function setDiagonal(int $diagonal): self
    {
        $this->diagonal = $diagonal;

        return $this;
    }

    public function isTouchScreen(): ?bool
    {
        return $this->touchScreen;
    }

    public function setTouchScreen(bool $touchScreen): self
    {
        $this->touchScreen = $touchScreen;

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
            $smartphone->setScreen($this);
        }

        return $this;
    }

    public function removeSmartphone(Smartphone $smartphone): self
    {
        if ($this->smartphones->removeElement($smartphone)) {
            // set the owning side to null (unless already changed)
            if ($smartphone->getScreen() === $this) {
                $smartphone->setScreen(null);
            }
        }

        return $this;
    }
}
