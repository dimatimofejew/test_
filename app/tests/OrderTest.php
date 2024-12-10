<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Orders;

class OrderTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }


    public function testExistApiDoc(): void
    {
        $response = static::createClient()->request('GET', '/api/doc');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testCreateOrder(): void
    {

        $fake_data = json_decode('{
  "hash": "hash",
  "userId": 1,
  "token": "token_token",
  "number": "#A123123",
  "status": 1,
  "email": "example@example.com",
  "vatType": 1,
  "vatNumber": "VAT12333123123",
  "taxNumber": "1233456789",
  "discount": 1,
  "delivery": 1.1,
  "deliveryType": 0,
  "deliveryTimeMin": "2024-11-25T16:10:46.072Z",
  "deliveryTimeMax": "2024-11-25T16:10:46.072Z",
  "deliveryTimeConfirmMin": "2024-11-25T16:10:46.072Z",
  "deliveryTimeConfirmMax": "2024-11-25T16:10:46.072Z",
  "deliveryTimeFastPayMin": "2024-11-25T16:10:46.072Z",
  "deliveryTimeFastPayMax": "2024-11-25T16:10:46.072Z",
  "deliveryOldTimeMin": "2024-11-25T16:10:46.072Z",
  "deliveryOldTimeMax": "2024-11-25T16:10:46.072Z",
  "deliveryIndex": "string_",
  "companyName": "Название компании",
  "payType": 1,
  "payDateExecution": "2024-11-25T16:10:46.072Z",
  "offsetDate": "2024-11-25T16:10:46.072Z",
  "offsetReason": 1,
  "proposedDate": "2024-11-25T16:10:46.072Z",
  "shipDate": "2024-11-25T16:10:46.072Z",
  "trackingNumber": "Номер треккинга",
  "locale": "pl",
  "curRate": 1,
  "currency": "EUR",
  "measure": "m",
  "name": "Название заказа",
  "description": "Дополнительная информация",
  "createDate": "2024-12-10T10:09:50.603Z",
  "updateDate": "2024-11-25T16:10:46.072Z",
  "step": 1,
  "addressEqual": 1,
  "bankTransferRequested": 1,
  "acceptPay": 1,
  "cancelDate": "2024-11-25T16:10:46.072Z",
  "weightGross": 1,
  "productReview": 1,
  "mirror": 2,
  "process": 0,
  "factDate": "2024-11-25T16:10:46.072Z",
  "entranceReview": 1,
  "paymentEuro": 1,
  "specPrice": 1,
  "showMsg": 1,
  "deliveryPriceEuro": 1.1,
  "addressPayer": 1,
  "sendingDate": "2024-11-25T16:10:46.072Z",
  "deliveryCalculateType": 0,
  "fullPaymentDate": "2024-11-25T16:10:46.072Z",
  "bankDetails": "number swift",
  "manager": {
    "managerName": "Имя",
    "managerEmail": "manager@email.com",
    "managerPhone": "+48 123 123 123"
  },
  "deliveryAddr": {
    "clientId": null,
    "countryId": 1,
    "region": "Московская обл.",
    "city": "Москова",
    "address": "Красная площадь",
    "building": "1блок",
    "phoneCode": "+7",
    "phone": "123-123-12333",
    "apartmentOffice": "кв 2 комната3"
  },
  "bilingAddr": {
    "clientId": null,
    "countryId": 1,
    "region": "Московская обл.",
    "city": "Москова",
    "address": "Красная площадь",
    "building": "1блок",
    "phoneCode": "+7",
    "phone": "123-123-12333",
    "apartmentOffice": "кв 2 комната3"
  },
  "client": {
    "sex": 1,
    "clientName": "Имя",
    "clientSurname": "Фамилия",
    "email": "example@example.com"
  },
  "carrier": {
    "carrierName": "Название транспортной компании",
    "carrierContactData": "Контактные данные транспортной компании"
  },
  "warehouse": {
    "warehouseData": "Данные склада: адрес, название, часы работы"
  }
}', true);
//print_r(json_decode($fake_data,true));
        $fake_response = json_decode('{
  "@context": "/api/contexts/Orders",
  "@type": "Orders",
  "hash": "hash",
  "userId": 1,
  "token": "token_token",
  "number": "#A123123",
  "status": 1
}', true);


        $response = static::createClient()->request('POST', 'http://localhost:8080/api/orders', ['json' => $fake_data,
            'headers' => [
                'Content-Type' => 'application/ld+json', // Заголовок для тела запроса
                'Accept' => 'application/ld+json',       // Заголовок для формата ответа
            ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains($fake_response);
        $this->assertMatchesRegularExpression('~^/api/orders/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Orders::class);
    }
}
