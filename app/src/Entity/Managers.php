<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\ManagersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'managers')]
#[ORM\Entity(repositoryClass: ManagersRepository::class)]
class Managers
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "manager_name", length: 20, nullable: true, options: ["comment" => "Имя менеджера сопровождающего заказ"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Имя'
        ]
    )]
    private ?string $managerName = null;

    #[ORM\Column(name: "manager_email", length: 30, nullable: true, options: ["comment" => "Email менеджера сопровождающего заказ"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'manager@email.com'
        ]
    )]
    private ?string $managerEmail = null;

    #[ORM\Column(name: "manager_phone", length: 20, nullable: true, options: ["comment" => "Телефон менеджера сопровождающего заказ"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '+48 123 123 123'
        ]
    )]
    private ?string $managerPhone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManagerName(): ?string
    {
        return $this->managerName;
    }

    public function setManagerName(?string $managerName): static
    {
        $this->managerName = $managerName;

        return $this;
    }

    public function getManagerEmail(): ?string
    {
        return $this->managerEmail;
    }

    public function setManagerEmail(?string $managerEmail): static
    {
        $this->managerEmail = $managerEmail;

        return $this;
    }

    public function getManagerPhone(): ?string
    {
        return $this->managerPhone;
    }

    public function setManagerPhone(?string $managerPhone): static
    {
        $this->managerPhone = $managerPhone;

        return $this;
    }
}
