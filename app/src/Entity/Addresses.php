<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Repository\AddressesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'addresses')]
#[ORM\Index(name: 'country_id', columns: ['country_id'])]
#[ORM\Entity(repositoryClass: AddressesRepository::class)]
class Addresses
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "client_id", nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => null
        ]
    )]
    private ?int $clientId = null;

    #[ORM\Column(name: "country_id", nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $countryId = null;

    #[ORM\Column(name: "region", length: 50, nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Московская обл.'
        ]
    )]
    private ?string $region = null;

    #[ORM\Column(name: "city", length: 200, nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Москова'
        ]
    )]
    private ?string $city = null;

    #[ORM\Column(name: "address", length: 300, nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Красная площадь'
        ]
    )]
    private ?string $address = null;

    #[ORM\Column(name: "building", length: 200, nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '1блок'
        ]
    )]
    private ?string $building = null;

    #[ORM\Column(name: "phone_code", length: 20, nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '+7'
        ]
    )]
    private ?string $phoneCode = null;

    #[ORM\Column(name: "phone", length: 20, nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '123-123-12333'
        ]
    )]
    private ?string $phone = null;

    #[ORM\Column(name: "apartment_office", length: 30, nullable: true, options: ["comment" => "Квартира/офис"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'кв 2 комната3'
        ]
    )]
    private ?string $apartmentOffice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): static
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    public function setCountryId(?int $countryId): static
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): static
    {
        $this->building = $building;

        return $this;
    }

    public function getPhoneCode(): ?string
    {
        return $this->phoneCode;
    }

    public function setPhoneCode(?string $phoneCode): static
    {
        $this->phoneCode = $phoneCode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getApartmentOffice(): ?string
    {
        return $this->apartmentOffice;
    }

    public function setApartmentOffice(?string $apartmentOffice): static
    {
        $this->apartmentOffice = $apartmentOffice;

        return $this;
    }
}
