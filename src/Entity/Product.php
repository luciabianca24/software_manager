<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private string $description;

    /**
     * @var Collection<int, License>
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: License::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $licenses;

    /**
     * @var Collection<int, Release>
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Release::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $releaseVersions;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
        $this->licenses = new ArrayCollection();
        $this->releaseVersions = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    /**
     * @return Collection<int, License>
     */
    public function getLicenses(): Collection
    {
        return $this->licenses;
    }

    public function addLicense(License $license): self
    {
        if (!$this->licenses->contains($license)) {
            $this->licenses->add($license);
            $license->setProduct($this);
        }

        return $this;
    }

    public function removeLicense(License $license): self
    {
        $this->licenses->removeElement($license);

        return $this;
    }

    /**
     * @return Collection<int, Release>
     */
    public function getReleaseVersions(): Collection
    {
        return $this->releaseVersions;
    }

    public function addReleaseVersion(Release $releaseVersion): self
    {
        if (!$this->releaseVersions->contains($releaseVersion)) {
            $this->releaseVersions->add($releaseVersion);
            $releaseVersion->setProduct($this);
        }

        return $this;
    }

    public function removeReleaseVersion(Release $releaseVersion): self
    {
        $this->releaseVersions->removeElement($releaseVersion);

        return $this;
    }
}
