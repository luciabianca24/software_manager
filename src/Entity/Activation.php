<?php

namespace App\Entity;
use App\Entity\LicenseStatus;
use App\Repository\LicenseRepository;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\EnsureProductionSettingsDoctrineCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ActivationRepository::class)]
#[ORM\Table(name: 'activations')]
class Activation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'activations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Product $product;
    #[ORM\Column(type:'string', length: 255)]
    private string $url;
    #[ORM\Column(type:'string', length: 255)]
    private string $version;
    #[ORM\Column(type:'datetime_immutable')]
    private DateTimeImmutable $createdAt;
    public function __construct(string $url, string $version, Product $product)
    {
        $this->url = $url;
        $this->version = $version;
        $this->product = $product;
        $this->createdAt = new DateTimeImmutable();
    }
    public function getId(): ?Uuid
    {
        return $this->id;
    }
    public function getUrl(): string
    {
        return $this->url;
    }
    public function getVersion(): string
    {
        return $this->version;
    }
    public function getProduct():Product
    {
        return $this->product;
    }
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }
    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }
}
