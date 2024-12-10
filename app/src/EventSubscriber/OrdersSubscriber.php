<?php

namespace App\EventSubscriber;

use App\Entity\Orders;
use App\Service\OrderService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Entity;

class OrdersSubscriber implements EventSubscriber
{
    private OrderService $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    /**
     * Возвращает список событий, на которые подписан этот Subscriber.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * Обрабатывает событие создания (prePersist).
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        // Проверяем, что это сущность Orders
        if (!$entity instanceof Orders) {
            return;
        }
        $entity = $this->orderService->setDataOrder($entity);

    }

    /**
     * Обрабатывает событие обновления (preUpdate).
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        // Проверяем, что это сущность Orders
        if (!$entity instanceof Orders) {
            return;
        }

        // Получаем данные изменений
        $changes = $args->getEntityChangeSet();
        // Проходим по всем полям и устанавливаем значения
        $this->orderService->setDataOrder($entity, $changes);

    }
}
