<?php

namespace App\Service;

use App\Entity\Orders;
use App\Entity\Managers;
use App\Entity\Addresses;
use App\Entity\Warehouses;
use App\Entity\Carriers;
use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class OrderService
{
private EntityManagerInterface $entityManager;

    private SerializerInterface $serializer;

public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
{
    $this->serializer=$serializer;
$this->entityManager = $entityManager;

}


    public function order($order):string
    {
        $orderresult = $this->serializer->deserialize(json_encode($order), Orders::class,'json');
        $this->entityManager->persist($orderresult);
        $this->entityManager->flush();
                return "Result: ".json_encode($orderresult->getId());
    }

    public function setDataOrder(Orders $order, array $changes=[]): Orders
    {
        if ( $order->getId()) {
            // Только если заказ уже существует (имеет ID), обновляем дату изменения
            $order->setUpdateDate(new \DateTime('now'));


            foreach ($changes as $field => [$oldValue, $newValue]) {
                // Если поле не обновляется, возвращаем старое значение
                if ($newValue === null || $newValue === '') {
                    $setter = 'set' . ucfirst($field);
                    $getter = 'get' . ucfirst($field);

                    if (method_exists($order, $getter) && method_exists($order, $setter)) {
                        $order->$setter($oldValue);
                    }
                }
            }

        }else{
            $order->setCreateDate(new \DateTime());
        }


        // Для менеджера
        $this->assignExistingEntity($order, 'Manager', Managers::class);

        // Для клиента если userID не продавец, а клиент то можно сюда связать более жестоко с проверкой ID
        //надеюсь мейл был клиента, хоть раз так много полей не обязательных. то фо факту главное чтоб была сумиа, а клиент всегда прав
        //$this->assignExistingEntity($order, 'Client', Clients::class, false);
        $this->assignExistingEntity($order, 'Client', Clients::class);
        if($order->getClient()->getId()){
            $order->getBilingAddr()->setClientId($order->getClient()->getId());
            $order->getDeliveryAddr()->setClientId($order->getClient()->getId());
        }
        // адреса равны
        if($order->getAddressEqual()){
            $order->setBilingAddr($order->getDeliveryAddr());
        }
        // здесь же можно поиграться и перенести время доставки в адрес и тогда при поиске по адресу сможем заполнять время прошлой доставки макс мин (если конечно это все не решает клиент API и зачем-то кидает нам)

        $this->assignExistingEntity($order, 'Warehouse', Warehouses::class); //эти данные меняются редко
        $this->assignExistingEntity($order, 'DeliveryAddr', Addresses::class);
        $this->assignExistingEntity($order, 'Carrier', Carriers::class); //эти данные меняются редко
        $this->assignExistingEntity($order, 'BilingAddr', Addresses::class);
        //можно было бы вывести по примеру Старны /регионы /города и связать их, но используем в данном случае как данные заказа и адрес в заказе должен остаться даже после переименования страны региона города и сноса дома
        // но мы сможем их использовать для посказки кленту.

            // Возвращаем обновленный заказ
        return $order;
    }


    public function assignExistingEntity($order, $entity, $entityClass, $id_ignore=true): void
    {
        // Получаем объект, например, менеджера или клиента
        $entityData = $order->{'get' . ucfirst($entity)}();

        // Если объект существует, продолжаем
        if ($entityData) {
            // Формируем массив для поиска
            $criteria = [];

            // Получаем все публичные геттеры объекта
            $methods = get_class_methods($entityData);

            // Ищем все методы вида "get" и добавляем в критерии
            foreach ($methods as $method) {
                if (strpos($method, 'get') === 0 && (!$id_ignore||$method!='getId')) {
                    // Получаем значение свойства через геттер
                    $value = $entityData->$method();
                    // Добавляем в критерии, убираем "get" и делаем первое слово с маленькой буквы
                    $fieldName = lcfirst(substr($method, 3));
                    $criteria[$fieldName] = $value;
                }
            }

            // Ищем существующий объект в базе данных
            $existingEntity = $this->entityManager->getRepository($entityClass)->findOneBy($criteria);

            // Если объект найден, переназначаем его
            if ($existingEntity) {
                $setter = 'set' . ucfirst($entity);
                if (method_exists($order, $setter)) {
                    $order->$setter($existingEntity);
                }
            }
        }
    }
    public function getMeta($className){
        $metadata = $this->entityManager->getClassMetadata($className);
        $fieldMappings = $metadata->fieldMappings;
        $xsdElements = [];
        foreach ($fieldMappings as $field => $mapping) {

            $xsdElements[$mapping->fieldName]=$mapping->type;
        }
        return $xsdElements;
    }
}
