<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter as ApiOrmFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BikeRepository")
 * @UniqueEntity("licenseNumber", groups={"Default"})
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post" ={"controller"=App\Controller\APi\Bike\BikeResponsibleAssigner::class},
 *      },
 *     itemOperations={
 *          "get",
 *          "put"={"controller"=App\Controller\APi\Bike\BikeResponsibleAssigner::class},
 *          "delete"={"controller"=App\Controller\APi\Bike\BikeDestroyer::class},
 *          "respolve"={
 *              "controller"=App\Controller\APi\Bike\BikeStealingResolver::class,
 *              "method"="put",
 *              "path"="/bikes/{id}/resolve",
 *              "denormalization_context"={"groups"={"bike:item:resolve"}}
 *          }
 *      },
 *     normalizationContext={"groups"={"bike:read"}, "swagger_definition_name": "Read"},
 *     denormalizationContext={"groups"={"bike:write"}, "swagger_definition_name": "Write"},
 *     attributes={"pagination_items_per_page"=10}
 * )
 * @ApiFilter(ApiOrmFilter\DateFilter::class, properties={"stealingDate"})
 * @ApiFilter(ApiOrmFilter\SearchFilter::class,
 *      properties={
 *          "licenseNumber": "exact",
 *          "color": "exact",
 *          "type": "exact",
 *          "stealingDescription": "partial",
 *          "ownerFullName": "partial",
 *          "responsible": "exact"
 *      })
 * @ApiFilter(ApiOrmFilter\BooleanFilter::class, properties={"isResolved"})
 * @ApiFilter(ApiOrmFilter\ExistsFilter::class, properties={"responsible"})
 * @ApiFilter(ApiOrmFilter\OrderFilter::class, properties={"id", "stealingDate"}, arguments={"orderParameterName"="order"})
 */
class Bike
{
    const AVAILABLE_COLORS = ['red', 'green', 'blue', 'black', 'white', 'multi-color'];
    const AVAILABLE_TYPES = ['sport', 'road', 'speed', 'mountain', 'hybrid', 'folding'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"bike:read", "bike:write", "police:item:get"})
     */
    private $licenseNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Groups({"bike:read", "bike:write", "police:item:get"})
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Groups({"bike:read", "bike:write", "police:item:get"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Groups({"bike:read", "bike:write", "police:item:get"})
     */
    private $ownerFullName;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Groups({"bike:read", "bike:write", "police:item:get"})
     */
    private $stealingDate;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Groups({"bike:read", "bike:write", "police:item:get"})
     */
    private $stealingDescription;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     * @Groups({"bike:read", "police:item:get"})
     */
    private $isResolved = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Police", inversedBy="bikes")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Groups({"bike:read"})
     */
    private $responsible;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->licenseNumber;
    }

    public function setLicenseNumber(string $licenseNumber): self
    {
        $this->licenseNumber = $licenseNumber;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getOwnerFullName(): ?string
    {
        return $this->ownerFullName;
    }

    public function setOwnerFullName(string $ownerFullName): self
    {
        $this->ownerFullName = $ownerFullName;

        return $this;
    }

    public function getStealingDate(): ?\DateTimeInterface
    {
        return $this->stealingDate;
    }

    public function setStealingDate(\DateTimeInterface $stealingDate): self
    {
        $this->stealingDate = $stealingDate;

        return $this;
    }

    public function getStealingDescription(): ?string
    {
        return $this->stealingDescription;
    }

    public function setStealingDescription(string $stealingDescription): self
    {
        $this->stealingDescription = $stealingDescription;

        return $this;
    }

    public function getIsResolved(): ?bool
    {
        return $this->isResolved;
    }

    public function setIsResolved(bool $isResolved): self
    {
        $this->isResolved = $isResolved;

        return $this;
    }

    public function getResponsible(): ?Police
    {
        return $this->responsible;
    }

    public function setResponsible(?Police $responsible): self
    {
        $this->responsible = $responsible;

        return $this;
    }
}
