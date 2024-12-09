<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\ClientsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'clients')]
#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "sex", type: Types::SMALLINT, nullable: true, options: ["comment" => "Пол клиента"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $sex = null;

    #[ORM\Column(name: "client_name", length: 255, nullable: true, options: ["comment" => "Имя клиента"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Имя'
        ]
    )]
    private ?string $clientName = null;

    #[ORM\Column(name: "client_surname", length: 255, nullable: true, options: ["comment" => "Фамилия клиента"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Фамилия'
        ]
    )]
    private ?string $clientSurname = null;

    #[ORM\Column(name: "email", length: 100, nullable: true, options: ["comment" => "контактный E-mail"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'example@example.com'
        ]
    )]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSex(): ?int
    {
        return $this->sex;
    }

    public function setSex(?int $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): static
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getClientSurname(): ?string
    {
        return $this->clientSurname;
    }

    public function setClientSurname(?string $clientSurname): static
    {
        $this->clientSurname = $clientSurname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
