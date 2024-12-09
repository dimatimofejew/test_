<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use App\Controller\CreateOrdersController;
use App\Repository\OrdersRepository;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\OpenApi\Model;
use App\Entity\Managers;
use App\Entity\Carriers;
use App\Entity\Clients;
use App\Entity\Addresses;
use App\Entity\Warehouses;

#[ORM\Table(name: 'orders')]
#[ORM\Index(name: 'IDX_2', columns: ['user_id'])]
#[ORM\Index(name: 'IDX_3', columns: ['create_date'])]
#[ORM\Index(name: 'IDX_4', columns: ['create_date', 'status'])]
#[ORM\Index(name: 'IDX_5', columns: ['hash'])]
#[ORM\Entity(repositoryClass: OrdersRepository::class)]
#[ApiResource()]
#[ORM\HasLifecycleCallbacks]
class Orders
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "hash", length: 32, options: ["comment" => "hash заказа"])]
    #[Assert\NotBlank]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'hash'
        ]
    )]
    private ?string $hash = null;

    #[ORM\Column(name: "user_id", nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => '1'
        ]
    )]
    private ?int $userId = null;

    #[ORM\Column(name: "token", length: 64, options: ["comment" => "уникальный хеш пользователя"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'token_token'
        ]
    )]
    private ?string $token = null;

    #[ORM\Column(name: "number", length: 10, nullable: true, options: ["comment" => "Номер заказа"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '#A123123'
        ]
    )]
    private ?string $number = null;

    #[ORM\Column(name: "status", options: ["comment" => "Статус заказа", "default" => 1])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => '1'
        ]
    )]
    private ?int $status = 1;

    #[ORM\Column(name: "email", length: 100, nullable: true, options: ["comment" => "контактный E-mail"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'example@example.com'
        ]
    )]
    private ?string $email = null;

    #[ORM\Column(name: "vat_type", options: ["comment" => "Частное лицо или плательщик НДС", "default" => 0])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $vatType = 0;

    #[ORM\Column(name: "vat_number", length: 100, nullable: true, options: ["comment" => "НДС-номер"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'VAT12333123123'
        ]
    )]
    private ?string $vatNumber = null;

    #[ORM\Column(name: "tax_number", length: 50, nullable: true, options: ["comment" => "Индивидуальный налоговый номер налогоплательщика"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '1233456789'
        ]
    )]
    private ?string $taxNumber = null;

    #[ORM\Column(name: "discount", type: Types::SMALLINT, nullable: true, options: ["comment" => "Процент скидки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => '1'
        ]
    )]
    private ?int $discount = null;

    #[ORM\Column(name: "delivery", type: Types::FLOAT, nullable: true, options: ["comment" => "Стоимость доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'example' => 1
        ]
    )]
    private ?float $delivery = null;

    #[ORM\Column(name: "delivery_type", nullable: true, options: ["comment" => "Тип доставки: 0 - адрес клинта, 1 - адрес склада", "default" => 0])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 0
        ]
    )]
    private ?int $deliveryType = 0;

    #[ORM\Column(name: "delivery_time_min", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Минимальный срок доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryTimeMin = null;

    #[ORM\Column(name: "delivery_time_max", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Максимальный срок доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryTimeMax = null;

    #[ORM\Column(name: "delivery_time_confirm_min", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Минимальный срок доставки подтверждённый производителем"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryTimeConfirmMin = null;

    #[ORM\Column(name: "delivery_time_confirm_max", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Максимальный срок доставки подтверждённый производителем"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryTimeConfirmMax = null;

    #[ORM\Column(name: "delivery_time_fast_pay_min", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Минимальный срок доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryTimeFastPayMin = null;

    #[ORM\Column(name: "delivery_time_fast_pay_max", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Максимальный срок доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryTimeFastPayMax = null;

    #[ORM\Column(name: "delivery_old_time_min", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Прошлый минимальный срок доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryOldTimeMin = null;

    #[ORM\Column(name: "delivery_old_time_max", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Прошлый максимальный срок доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $deliveryOldTimeMax = null;

    #[ORM\Column(name: "delivery_index", length: 20, nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'string_'
        ]
    )]
    private ?string $deliveryIndex = null;

    #[ORM\Column(name: "company_name", length: 255, nullable: true, options: ["comment" => "Название компании"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Название компании'
        ]
    )]
    private ?string $companyName = null;

    #[ORM\Column(name: "pay_type", type: Types::SMALLINT, options: ["comment" => "Выбранный тип оплаты"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => '1'
        ]
    )]
    private ?int $payType = null;

    #[ORM\Column(name: "pay_date_execution", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Дата до которой действует текущая цена заказа"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $payDateExecution = null;

    #[ORM\Column(name: "offset_date", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Дата сдвига предполагаемого расчета доставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $offsetDate = null;

    #[ORM\Column(name: "offset_reason", type: Types::SMALLINT, nullable: true, options: ["comment" => "тип причина сдвига сроков 1 - каникулы на фабрике, 2 - фабрика уточняет сроки пр-ва, 3 - другое"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $offsetReason = null;

    #[ORM\Column(name: "proposed_date", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Предполагаемая дата поставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $proposedDate = null;

    #[ORM\Column(name: "ship_date", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Предполагаемая дата отгрузки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $shipDate = null;

    #[ORM\Column(name: "tracking_number", length: 50, nullable: true, options: ["comment" => "Номер треккинга"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Номер треккинга'
        ]
    )]
    private ?string $trackingNumber = null;

    #[ORM\Column(name: "locale", length: 5, options: ["comment" => "локаль из которой был оформлен заказ"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'pl'
        ]
    )]
    private ?string $locale = null;

    #[ORM\Column(name: "cur_rate", type: Types::FLOAT,  nullable: true, options: ["comment" => "курс на момент оплаты", "default" => 1.000000])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'example' => '1.000000'
        ]
    )]
    private ?float $curRate = 1.000000;

    #[ORM\Column(name: "currency", length: 3, options: ["comment" => "валюта при которой был оформлен заказ", "default" => 'EUR'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'EUR'
        ]
    )]
    private ?string $currency = 'EUR';

    #[ORM\Column(name: "measure", length: 3, options: ["comment" => "ед. изм. в которой был оформлен заказ", "default" => 'm'])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'm'
        ]
    )]
    private ?string $measure = 'm';

    #[ORM\Column(name: "name", length: 200, options: ["comment" => "Название заказа"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Название заказа'
        ]
    )]
    private ?string $name = null;

    #[ORM\Column(name: "description", length: 1000, nullable: true, options: ["comment" => "Дополнительная информация"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Дополнительная информация'
        ]
    )]
    private ?string $description = null;

    #[ORM\Column(name: "create_date", type: Types::DATETIME_MUTABLE, options: ["comment" => "Дата создания"])]
    private ?\DateTimeInterface $createDate = null;

    #[ORM\Column(name: "update_date", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Дата изменения"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $updateDate = null;


    #[ORM\Column(name: "step", options: ["comment" => "если true то заказ не будет сброшен в следствии изменений", "default" => 1])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $step = 1;

    #[ORM\Column(name: "address_equal", nullable: true, options: ["comment" => "Адреса плательщика и получателя совпадают (false - разные, true - одинаковые )", "default" => 1])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]

    private ?int $addressEqual = 1;

    #[ORM\Column(name: "bank_transfer_requested", nullable: true, options: ["comment" => "Запрашивался ли счет на банковский перевод"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $bankTransferRequested = null;

    #[ORM\Column(name: "accept_pay", nullable: true, options: ["comment" => "Если true то заказ отправлен в работу"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $acceptPay = null;

    #[ORM\Column(name: "cancel_date", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Конечная дата согласования сроков поставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $cancelDate = null;

    #[ORM\Column(name: "weight_gross", nullable: true, options: ["comment" => "Общий вес брутто заказа"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'example' => 1
        ]
    )]
    private ?float $weightGross = null;

    #[ORM\Column(name: "product_review", nullable: true, options: ["comment" => "Оставлен отзыв по коллекциям в заказе"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $productReview = null;

    #[ORM\Column(name: "mirror", type: Types::SMALLINT, nullable: true, options: ["comment" => "Метка зеркала на котором создается заказ"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 2
        ]
    )]
    private ?int $mirror = null;

    #[ORM\Column(name: "process", nullable: true, options: ["comment" => "метка массовой обработки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 0
        ]
    )]
    private ?int $process = null;

    #[ORM\Column(name: "fact_date", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Фактическая дата поставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $factDate = null;

    #[ORM\Column(name: "entrance_review", type: Types::SMALLINT, nullable: true, options: ["comment" => "Фиксирует вход клиента на страницу отзыва и последующие клики"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $entranceReview = null;

    #[ORM\Column(name: "payment_euro", nullable: true, options: ["comment" => "Если true, то оплату посчитать в евро", "default" => 0])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $paymentEuro = 0;

    #[ORM\Column(name: "spec_price", nullable: true, options: ["comment" => "установлена спец цена по заказу"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $specPrice = null;

    #[ORM\Column(name: "show_msg", nullable: true, options: ["comment" => "Показывать спец. сообщение"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $showMsg = null;

    #[ORM\Column(name: "delivery_price_euro", nullable: true, options: ["comment" => "Стоимость доставки в евро"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'float',
            'example' => 1.1
        ]
    )]
    private ?float $deliveryPriceEuro = null;

    #[ORM\Column(name: "address_payer", nullable: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 1
        ]
    )]
    private ?int $addressPayer = null;

    #[ORM\Column(name: "sending_date", type: Types::DATETIME_MUTABLE, nullable: true, options: ["comment" => "Расчетная дата поставки"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $sendingDate = null;

    #[ORM\Column(name: "delivery_calculate_type", nullable: true, options: ["comment" => "Тип расчета: 0 - ручной, 1 - автоматический", "default" => 0])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'integer',
            'example' => 0
        ]
    )]
    private ?int $deliveryCalculateType = 0;

    #[ORM\Column(name: "full_payment_date", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Дата полной оплаты заказа"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'format' => 'date-time',
            'example' => '2024-11-25T16:10:46.072Z'
        ]
    )]
    private ?\DateTimeInterface $fullPaymentDate = null;

    #[ORM\Column(name: "bank_details", type: Types::TEXT, nullable: true, options: ["comment" => "Реквизиты банка для возврата средств"])]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'number swift'
        ]
    )]
    private ?string $bankDetails = null;

    #[ORM\ManyToOne(targetEntity: Managers::class, cascade: ["persist"])]
    private ?Managers $manager = null;

    #[ORM\ManyToOne(targetEntity: Addresses::class, cascade: ["persist"])]
    private ?Addresses $deliveryAddr = null;

    #[ORM\ManyToOne(targetEntity: Addresses::class, cascade: ["persist"])]
    private ?Addresses $bilingAddr = null;

    #[ORM\ManyToOne(targetEntity: Clients::class, cascade: ["persist"])]
    private ?Clients $client = null;

    #[ORM\ManyToOne(targetEntity: Carriers::class, cascade: ["persist"])]
    private ?Carriers $carrier = null;

    #[ORM\ManyToOne(targetEntity: Warehouses::class, cascade: ["persist"])]
    private ?Warehouses $warehouse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

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

    public function getVatType(): ?int
    {
        return $this->vatType;
    }

    public function setVatType(int $vatType): static
    {
        $this->vatType = $vatType;

        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): static
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): static
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(?string $delivery): static
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getDeliveryType(): ?int
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(?int $deliveryType): static
    {
        $this->deliveryType = $deliveryType;

        return $this;
    }

    public function getDeliveryTimeMin(): ?\DateTimeInterface
    {
        return $this->deliveryTimeMin;
    }

    public function setDeliveryTimeMin(?\DateTimeInterface $deliveryTimeMin): static
    {
        $this->deliveryTimeMin = $deliveryTimeMin;

        return $this;
    }

    public function getDeliveryTimeMax(): ?\DateTimeInterface
    {
        return $this->deliveryTimeMax;
    }

    public function setDeliveryTimeMax(?\DateTimeInterface $deliveryTimeMax): static
    {
        $this->deliveryTimeMax = $deliveryTimeMax;

        return $this;
    }

    public function getDeliveryTimeConfirmMin(): ?\DateTimeInterface
    {
        return $this->deliveryTimeConfirmMin;
    }

    public function setDeliveryTimeConfirmMin(?\DateTimeInterface $deliveryTimeConfirmMin): static
    {
        $this->deliveryTimeConfirmMin = $deliveryTimeConfirmMin;

        return $this;
    }

    public function getDeliveryTimeConfirmMax(): ?\DateTimeInterface
    {
        return $this->deliveryTimeConfirmMax;
    }

    public function setDeliveryTimeConfirmMax(?\DateTimeInterface $deliveryTimeConfirmMax): static
    {
        $this->deliveryTimeConfirmMax = $deliveryTimeConfirmMax;

        return $this;
    }

    public function getDeliveryTimeFastPayMin(): ?\DateTimeInterface
    {
        return $this->deliveryTimeFastPayMin;
    }

    public function setDeliveryTimeFastPayMin(?\DateTimeInterface $deliveryTimeFastPayMin): static
    {
        $this->deliveryTimeFastPayMin = $deliveryTimeFastPayMin;

        return $this;
    }

    public function getDeliveryTimeFastPayMax(): ?\DateTimeInterface
    {
        return $this->deliveryTimeFastPayMax;
    }

    public function setDeliveryTimeFastPayMax(?\DateTimeInterface $deliveryTimeFastPayMax): static
    {
        $this->deliveryTimeFastPayMax = $deliveryTimeFastPayMax;

        return $this;
    }

    public function getDeliveryOldTimeMin(): ?\DateTimeInterface
    {
        return $this->deliveryOldTimeMin;
    }

    public function setDeliveryOldTimeMin(?\DateTimeInterface $deliveryOldTimeMin): static
    {
        $this->deliveryOldTimeMin = $deliveryOldTimeMin;

        return $this;
    }

    public function getDeliveryOldTimeMax(): ?\DateTimeInterface
    {
        return $this->deliveryOldTimeMax;
    }

    public function setDeliveryOldTimeMax(?\DateTimeInterface $deliveryOldTimeMax): static
    {
        $this->deliveryOldTimeMax = $deliveryOldTimeMax;

        return $this;
    }

    public function getDeliveryIndex(): ?string
    {
        return $this->deliveryIndex;
    }

    public function setDeliveryIndex(?string $deliveryIndex): static
    {
        $this->deliveryIndex = $deliveryIndex;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getPayType(): ?int
    {
        return $this->payType;
    }

    public function setPayType(int $payType): static
    {
        $this->payType = $payType;

        return $this;
    }

    public function getPayDateExecution(): ?\DateTimeInterface
    {
        return $this->payDateExecution;
    }

    public function setPayDateExecution(?\DateTimeInterface $payDateExecution): static
    {
        $this->payDateExecution = $payDateExecution;

        return $this;
    }

    public function getOffsetDate(): ?\DateTimeInterface
    {
        return $this->offsetDate;
    }

    public function setOffsetDate(?\DateTimeInterface $offsetDate): static
    {
        $this->offsetDate = $offsetDate;

        return $this;
    }

    public function getOffsetReason(): ?int
    {
        return $this->offsetReason;
    }

    public function setOffsetReason(?int $offsetReason): static
    {
        $this->offsetReason = $offsetReason;

        return $this;
    }

    public function getProposedDate(): ?\DateTimeInterface
    {
        return $this->proposedDate;
    }

    public function setProposedDate(?\DateTimeInterface $proposedDate): static
    {
        $this->proposedDate = $proposedDate;

        return $this;
    }

    public function getShipDate(): ?\DateTimeInterface
    {
        return $this->shipDate;
    }

    public function setShipDate(?\DateTimeInterface $shipDate): static
    {
        $this->shipDate = $shipDate;

        return $this;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(?string $trackingNumber): static
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function getCurRate(): ?string
    {
        return $this->curRate;
    }

    public function setCurRate(?string $curRate): static
    {
        $this->curRate = $curRate;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getMeasure(): ?string
    {
        return $this->measure;
    }

    public function setMeasure(string $measure): static
    {
        $this->measure = $measure;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): static
    {
        $this->updateDate = $updateDate;

        return $this;
    }


    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function getAddressEqual(): ?int
    {
        return $this->addressEqual;
    }

    public function setAddressEqual(?int $addressEqual): static
    {
        $this->addressEqual = $addressEqual;

        return $this;
    }

    public function getBankTransferRequested(): ?int
    {
        return $this->bankTransferRequested;
    }

    public function setBankTransferRequested(?int $bankTransferRequested): static
    {
        $this->bankTransferRequested = $bankTransferRequested;

        return $this;
    }

    public function getAcceptPay(): ?int
    {
        return $this->acceptPay;
    }

    public function setAcceptPay(?int $acceptPay): static
    {
        $this->acceptPay = $acceptPay;

        return $this;
    }

    public function getCancelDate(): ?\DateTimeInterface
    {
        return $this->cancelDate;
    }

    public function setCancelDate(?\DateTimeInterface $cancelDate): static
    {
        $this->cancelDate = $cancelDate;

        return $this;
    }

    public function getWeightGross(): ?float
    {
        return $this->weightGross;
    }

    public function setWeightGross(?float $weightGross): static
    {
        $this->weightGross = $weightGross;

        return $this;
    }

    public function getProductReview(): ?int
    {
        return $this->productReview;
    }

    public function setProductReview(?int $productReview): static
    {
        $this->productReview = $productReview;

        return $this;
    }

    public function getMirror(): ?int
    {
        return $this->mirror;
    }

    public function setMirror(?int $mirror): static
    {
        $this->mirror = $mirror;

        return $this;
    }

    public function getProcess(): ?int
    {
        return $this->process;
    }

    public function setProcess(?int $process): static
    {
        $this->process = $process;

        return $this;
    }

    public function getFactDate(): ?\DateTimeInterface
    {
        return $this->factDate;
    }

    public function setFactDate(?\DateTimeInterface $factDate): static
    {
        $this->factDate = $factDate;

        return $this;
    }

    public function getEntranceReview(): ?int
    {
        return $this->entranceReview;
    }

    public function setEntranceReview(?int $entranceReview): static
    {
        $this->entranceReview = $entranceReview;

        return $this;
    }

    public function getPaymentEuro(): ?int
    {
        return $this->paymentEuro;
    }

    public function setPaymentEuro(?int $paymentEuro): static
    {
        $this->paymentEuro = $paymentEuro;

        return $this;
    }

    public function getSpecPrice(): ?int
    {
        return $this->specPrice;
    }

    public function setSpecPrice(?int $specPrice): static
    {
        $this->specPrice = $specPrice;

        return $this;
    }

    public function getShowMsg(): ?int
    {
        return $this->showMsg;
    }

    public function setShowMsg(?int $showMsg): static
    {
        $this->showMsg = $showMsg;

        return $this;
    }

    public function getDeliveryPriceEuro(): ?float
    {
        return $this->deliveryPriceEuro;
    }

    public function setDeliveryPriceEuro(?float $deliveryPriceEuro): static
    {
        $this->deliveryPriceEuro = $deliveryPriceEuro;

        return $this;
    }

    public function getAddressPayer(): ?int
    {
        return $this->addressPayer;
    }

    public function setAddressPayer(?int $addressPayer): static
    {
        $this->addressPayer = $addressPayer;

        return $this;
    }

    public function getSendingDate(): ?\DateTimeInterface
    {
        return $this->sendingDate;
    }

    public function setSendingDate(?\DateTimeInterface $sendingDate): static
    {
        $this->sendingDate = $sendingDate;

        return $this;
    }

    public function getDeliveryCalculateType(): ?int
    {
        return $this->deliveryCalculateType;
    }

    public function setDeliveryCalculateType(?int $deliveryCalculateType): static
    {
        $this->deliveryCalculateType = $deliveryCalculateType;

        return $this;
    }

    public function getFullPaymentDate(): ?\DateTimeInterface
    {
        return $this->fullPaymentDate;
    }

    public function setFullPaymentDate(?\DateTimeInterface $fullPaymentDate): static
    {
        $this->fullPaymentDate = $fullPaymentDate;

        return $this;
    }

    public function getBankDetails(): ?string
    {
        return $this->bankDetails;
    }

    public function setBankDetails(?string $bankDetails): static
    {
        $this->bankDetails = $bankDetails;

        return $this;
    }

    public function getManager(): ?Managers
    {
        return $this->manager;
    }

    public function setManager(?Managers $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function getDeliveryAddr(): ?Addresses
    {
        return $this->deliveryAddr;
    }

    public function setDeliveryAddr(?Addresses $deliveryAddr): static
    {
        $this->deliveryAddr = $deliveryAddr;

        return $this;
    }

    public function getBilingAddr(): ?Addresses
    {
        return $this->bilingAddr;
    }

    public function setBilingAddr(?Addresses $bilingAddr): static
    {
        $this->bilingAddr = $bilingAddr;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCarrier(): ?Carriers
    {
        return $this->carrier;
    }

    public function setCarrier(?Carriers $carrier): static
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getWarehouse(): ?Warehouses
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouses $warehouse): static
    {
        $this->warehouse = $warehouse;

        return $this;
    }


}
