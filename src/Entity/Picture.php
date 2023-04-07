<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
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
    private ?string $fileName = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Smartphone $smartphone = null;

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

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getSmartphone(): ?Smartphone
    {
        return $this->smartphone;
    }

    public function setSmartphone(?Smartphone $smartphone): self
    {
        $this->smartphone = $smartphone;

        return $this;
    }
}
