<?php

namespace App\EventSubscriber;

use App\Entity\Orders;
use App\Service\OrderService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

class OrdersSubscriber
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    #[AsDoctrineListener(event: 'prePersist')]
    public function onPrePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // Проверяем, что это сущность Orders
        if (!$entity instanceof Orders) {
            return;
        }

        // Устанавливаем данные заказа
        $this->orderService->setDataOrder($entity);
    }

    #[AsDoctrineListener(event: 'preUpdate')]
    public function onPreUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        // Проверяем, что это сущность Orders
        if (!$entity instanceof Orders) {
            return;
        }

        // Получаем изменения и обновляем данные
        $changes = $args->getEntityChangeSet();
        $this->orderService->setDataOrder($entity, $changes);
    }
}
