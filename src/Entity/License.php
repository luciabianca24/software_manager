<?php

namespace App\Entity;

use App\Entity\LicenseStatus;
use App\Repository\LicenseRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LicenseRepository::class)]
#[ORM\Table(name: 'licenses')]
class License
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'licenses')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'licenses')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    /**
     * @var Collection<int, Activation>
     */
    #[ORM\OneToMany(mappedBy: 'license', targetEntity: Activation::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $activations;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $description;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $expireAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::INTEGER)]
    private int $numberOfDevices;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true, nullable: true)]
    private ?string $licenseKey = null;

    #[ORM\Column(type: Types::STRING, enumType: LicenseStatus::class)]
    private LicenseStatus $licenseStatus;

    public function __construct(
        string $name,
        string $description,
        ?DateTimeImmutable $expireAt,
        int $numberOfDevices,
        LicenseStatus $licenseStatus,
        Product $product,
        User $user
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->expireAt = $expireAt;
        $this->numberOfDevices = $numberOfDevices;
        $this->licenseStatus = $licenseStatus;
        $this->product = $product;
        $this->user = $user;
        $this->createdAt = new DateTimeImmutable();
        $this->activations = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getExpireAt(): ?DateTimeImmutable
    {
        return $this->expireAt;
    }

    public function setExpireAt(?DateTimeImmutable $expireAt): self
    {
        $this->expireAt = $expireAt;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getNumberOfDevices(): int
    {
        return $this->numberOfDevices;
    }

    public function setNumberOfDevices(int $numberOfDevices): self
    {
        $this->numberOfDevices = $numberOfDevices;
        return $this;
    }

    public function getLicenseKey(): ?string
    {
        return $this->licenseKey;
    }

    public function setLicenseKey(?string $licenseKey): self
    {
        $this->licenseKey = $licenseKey;
        return $this;
    }

    public function getLicenseStatus(): LicenseStatus
    {
        return $this->licenseStatus;
    }

    public function setLicenseStatus(LicenseStatus $licenseStatus): self
    {
        $this->licenseStatus = $licenseStatus;
        return $this;
    }

    /**
     * @return Collection<int, Activation>
     */
    public function getActivations(): Collection
    {
        return $this->activations;
    }

    public function addActivation(Activation $activation): self
    {
        if (!$this->activations->contains($activation)) {
            $this->activations->add($activation);
            $activation->setLicense($this);
        }

        return $this;
    }

    public function removeActivation(Activation $activation): self
    {
        $this->activations->removeElement($activation);
        return $this;
    }
}
