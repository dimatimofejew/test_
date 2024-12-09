<?php

namespace App\Entity;

use App\Repository\OrdersArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'orders_article')]
#[ORM\Index(name: 'IDX_318C0B7C7294869C', columns: ['article_id'])]
#[ORM\Index(name: 'IDX_318C0B7C7FC358ED', columns: ['orders_id'])]
#[ORM\Entity(repositoryClass: OrdersArticleRepository::class)]
class OrdersArticle
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "orders_id", nullable: true)]
    private ?int $ordersId = null;

    #[ORM\Column(name: "article_id", nullable: true, options: ["comment" => "ID коллекции"])]
    private ?int $articleId = null;

    #[ORM\Column(name: "amount", options: ["comment" => "количество артикулов в ед. измерения"])]
    private ?float $amount = null;

    #[ORM\Column(name: "price", options: ["comment" => "Цена на момент оплаты заказа"])]
    private ?float $price = null;

    #[ORM\Column(name: "price_eur", nullable: true, options: ["comment" => "Цена в Евро по заказу"])]
    private ?float $priceEur = null;

    #[ORM\Column(name: "currency", length: 3, nullable: true, options: ["comment" => "Валюта для которой установлена цена"])]
    private ?string $currency = null;

    #[ORM\Column(name: "measure", length: 2, nullable: true, options: ["comment" => "Ед. изм. для которой установлена цена"])]
    private ?string $measure = null;

    #[ORM\Column(name: "delivery_time_min", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Минимальный срок доставки"])]
    private ?\DateTimeInterface $deliveryTimeMin = null;

    #[ORM\Column(name: "delivery_time_max", type: Types::DATE_MUTABLE, nullable: true, options: ["comment" => "Максимальный срок доставки"])]
    private ?\DateTimeInterface $deliveryTimeMax = null;

    #[ORM\Column(name: "weight", options: ["comment" => "вес упаковки"])]
    private ?float $weight = null;

    #[ORM\Column(name: "multiple_pallet", type: Types::SMALLINT, nullable: true, options: ["comment" => "Кратность палете, 1 - кратно упаковке, 2 - кратно палете, 3 - не меньше палеты"])]
    private ?int $multiplePallet = null;

    #[ORM\Column(name: "packaging_count", options: ["comment" => "Количество кратно которому можно добавлять товар в заказ"])]
    private ?float $packagingCount = null;

    #[ORM\Column(name: "pallet", options: ["comment" => "количество в палете на момент заказа"])]
    private ?float $pallet = null;

    #[ORM\Column(name: "packaging", options: ["comment" => "количество в упаковке"])]
    private ?float $packaging = null;

    #[ORM\Column(name: "swimming_pool", options: ["comment" => "Плитка специально для бассейна", "default" => 0])]
    private ?int $swimmingPool = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdersId(): ?int
    {
        return $this->ordersId;
    }

    public function setOrdersId(?int $ordersId): static
    {
        $this->ordersId = $ordersId;

        return $this;
    }

    public function getArticleId(): ?int
    {
        return $this->articleId;
    }

    public function setArticleId(?int $articleId): static
    {
        $this->articleId = $articleId;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceEur(): ?float
    {
        return $this->priceEur;
    }

    public function setPriceEur(?float $priceEur): static
    {
        $this->priceEur = $priceEur;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getMeasure(): ?string
    {
        return $this->measure;
    }

    public function setMeasure(?string $measure): static
    {
        $this->measure = $measure;

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

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getMultiplePallet(): ?int
    {
        return $this->multiplePallet;
    }

    public function setMultiplePallet(?int $multiplePallet): static
    {
        $this->multiplePallet = $multiplePallet;

        return $this;
    }

    public function getPackagingCount(): ?float
    {
        return $this->packagingCount;
    }

    public function setPackagingCount(float $packagingCount): static
    {
        $this->packagingCount = $packagingCount;

        return $this;
    }

    public function getPallet(): ?float
    {
        return $this->pallet;
    }

    public function setPallet(float $pallet): static
    {
        $this->pallet = $pallet;

        return $this;
    }

    public function getPackaging(): ?float
    {
        return $this->packaging;
    }

    public function setPackaging(float $packaging): static
    {
        $this->packaging = $packaging;

        return $this;
    }

    public function getSwimmingPool(): ?int
    {
        return $this->swimmingPool;
    }

    public function setSwimmingPool(int $swimmingPool): static
    {
        $this->swimmingPool = $swimmingPool;

        return $this;
    }
}
