<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\WarehousesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'warehouses')]
#[ORM\Entity(repositoryClass: WarehousesRepository::class)]
class Warehouses
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "warehouse_data", type: Types::TEXT, nullable: true, options: ["comment" => "Данные склада: адрес, название, часы работы"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Данные склада: адрес, название, часы работы'
        ]
    )]
    private ?string $warehouseData = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWarehouseData(): ?string
    {
        return $this->warehouseData;
    }

    public function setWarehouseData(?string $warehouseData): static
    {
        $this->warehouseData = $warehouseData;

        return $this;
    }
}
