<?php

namespace App\Entity;

use App\Repository\SmartphoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SmartphoneRepository::class)]
class Smartphone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read:smartphone')]

    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    #[Groups('read:smartphone')]
    private ?Uuid $uuid = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:smartphone')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2)]
    #[Groups('read:smartphone')]
    private ?string $price = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?\DateTimeImmutable $endedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:smartphone')]
    private ?string $technology = null;

    #[ORM\Column(length: 255)]
    #[Groups('read:smartphone')]
    private ?string $operatingSystem = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $specificAbsorptionRateMember = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $specificAbsorptionRateTrunk = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $specificAbsorptionRateHead = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $weight = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $height = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $depth = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $sparePartsAvailibility = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $indexRepairibility = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $ecoRatingDurability = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $ecoRatingClimateRespect = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $ecoRatingRepairability = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $ecoRatingResourcesPreservation = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $ecoRatingRecyclability = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?bool $microSdSlotMemory = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $romMemory = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $callAutonomy = null;

    #[ORM\Column]
    #[Groups('read:smartphone')]
    private ?int $batteryAutonomy = null;

    #[ORM\OneToMany(mappedBy: 'smartphone', targetEntity: Picture::class, orphanRemoval: true)]
    #[Groups('read:smartphone')]
    private Collection $pictures;

    #[ORM\ManyToOne(inversedBy: 'smartphones')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('read:smartphone')]
    private ?Range $range = null;

    #[ORM\ManyToMany(targetEntity: Camera::class, inversedBy: 'smartphones')]
    #[Groups('read:smartphone')]
    private Collection $cameras;

    #[ORM\ManyToMany(targetEntity: Color::class, inversedBy: 'smartphones')]
    #[Groups('read:smartphone')]
    private Collection $colors;

    #[ORM\ManyToOne(inversedBy: 'smartphones')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('read:smartphone')]
    private ?Screen $screen = null;

    #[ORM\ManyToMany(targetEntity: Customer::class, mappedBy: 'smartphones')]
    private Collection $customers;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->cameras = new ArrayCollection();
        $this->colors = new ArrayCollection();
        $this->customers = new ArrayCollection();
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(\DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getTechnology(): ?string
    {
        return $this->technology;
    }

    public function setTechnology(string $technology): self
    {
        $this->technology = $technology;

        return $this;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function setOperatingSystem(string $operatingSystem): self
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    public function getSpecificAbsorptionRateMember(): ?int
    {
        return $this->specificAbsorptionRateMember;
    }

    public function setSpecificAbsorptionRateMember(int $specificAbsorptionRateMember): self
    {
        $this->specificAbsorptionRateMember = $specificAbsorptionRateMember;

        return $this;
    }

    public function getSpecificAbsorptionRateTrunk(): ?int
    {
        return $this->specificAbsorptionRateTrunk;
    }

    public function setSpecificAbsorptionRateTrunk(int $specificAbsorptionRateTrunk): self
    {
        $this->specificAbsorptionRateTrunk = $specificAbsorptionRateTrunk;

        return $this;
    }

    public function getSpecificAbsorptionRateHead(): ?int
    {
        return $this->specificAbsorptionRateHead;
    }

    public function setSpecificAbsorptionRateHead(int $specificAbsorptionRateHead): self
    {
        $this->specificAbsorptionRateHead = $specificAbsorptionRateHead;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function setDepth(int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getSparePartsAvailibility(): ?int
    {
        return $this->sparePartsAvailibility;
    }

    public function setSparePartsAvailibility(int $sparePartsAvailibility): self
    {
        $this->sparePartsAvailibility = $sparePartsAvailibility;

        return $this;
    }

    public function getIndexRepairibility(): ?int
    {
        return $this->indexRepairibility;
    }

    public function setIndexRepairibility(int $indexRepairibility): self
    {
        $this->indexRepairibility = $indexRepairibility;

        return $this;
    }

    public function getEcoRatingDurability(): ?int
    {
        return $this->ecoRatingDurability;
    }

    public function setEcoRatingDurability(int $ecoRatingDurability): self
    {
        $this->ecoRatingDurability = $ecoRatingDurability;

        return $this;
    }

    public function getEcoRatingClimateRespect(): ?int
    {
        return $this->ecoRatingClimateRespect;
    }

    public function setEcoRatingClimateRespect(int $ecoRatingClimateRespect): self
    {
        $this->ecoRatingClimateRespect = $ecoRatingClimateRespect;

        return $this;
    }

    public function getEcoRatingRepairability(): ?int
    {
        return $this->ecoRatingRepairability;
    }

    public function setEcoRatingRepairability(int $ecoRatingRepairability): self
    {
        $this->ecoRatingRepairability = $ecoRatingRepairability;

        return $this;
    }

    public function getEcoRatingResourcesPreservation(): ?int
    {
        return $this->ecoRatingResourcesPreservation;
    }

    public function setEcoRatingResourcesPreservation(int $ecoRatingResourcesPreservation): self
    {
        $this->ecoRatingResourcesPreservation = $ecoRatingResourcesPreservation;

        return $this;
    }

    public function getEcoRatingRecyclability(): ?int
    {
        return $this->ecoRatingRecyclability;
    }

    public function setEcoRatingRecyclability(int $ecoRatingRecyclability): self
    {
        $this->ecoRatingRecyclability = $ecoRatingRecyclability;

        return $this;
    }

    public function isMicroSdSlotMemory(): ?bool
    {
        return $this->microSdSlotMemory;
    }

    public function setMicroSdSlotMemory(bool $microSdSlotMemory): self
    {
        $this->microSdSlotMemory = $microSdSlotMemory;

        return $this;
    }

    public function getRomMemory(): ?int
    {
        return $this->romMemory;
    }

    public function setRomMemory(int $romMemory): self
    {
        $this->romMemory = $romMemory;

        return $this;
    }

    public function getCallAutonomy(): ?int
    {
        return $this->callAutonomy;
    }

    public function setCallAutonomy(int $callAutonomy): self
    {
        $this->callAutonomy = $callAutonomy;

        return $this;
    }

    public function getBatteryAutonomy(): ?int
    {
        return $this->batteryAutonomy;
    }

    public function setBatteryAutonomy(int $batteryAutonomy): self
    {
        $this->batteryAutonomy = $batteryAutonomy;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setSmartphone($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getSmartphone() === $this) {
                $picture->setSmartphone(null);
            }
        }

        return $this;
    }

    public function getRange(): ?Range
    {
        return $this->range;
    }

    public function setRange(?Range $range): self
    {
        $this->range = $range;

        return $this;
    }

    /**
     * @return Collection<int, Camera>
     */
    public function getCameras(): Collection
    {
        return $this->cameras;
    }

    public function addCamera(Camera $camera): self
    {
        if (!$this->cameras->contains($camera)) {
            $this->cameras->add($camera);
        }

        return $this;
    }

    public function removeCamera(Camera $camera): self
    {
        $this->cameras->removeElement($camera);

        return $this;
    }

    /**
     * @return Collection<int, Color>
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors->add($color);
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        $this->colors->removeElement($color);

        return $this;
    }

    public function getScreen(): ?Screen
    {
        return $this->screen;
    }

    public function setScreen(?Screen $screen): self
    {
        $this->screen = $screen;

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
            $customer->addSmartphone($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            $customer->removeSmartphone($this);
        }

        return $this;
    }
}
