<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\CarriersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'carriers')]
#[ORM\Entity(repositoryClass: CarriersRepository::class)]
class Carriers
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "carrier_name", length: 50, nullable: true, options: ["comment" => "Название транспортной компании"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Название транспортной компании'
        ]
    )]
    private ?string $carrierName = null;

    #[ORM\Column(name: "carrier_contact_data", length: 255, nullable: true, options: ["comment" => "Контактные данные транспортной компании"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Контактные данные транспортной компании'
        ]
    )]
    private ?string $carrierContactData = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    public function setCarrierName(?string $carrierName): static
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    public function getCarrierContactData(): ?string
    {
        return $this->carrierContactData;
    }

    public function setCarrierContactData(?string $carrierContactData): static
    {
        $this->carrierContactData = $carrierContactData;

        return $this;
    }
}
