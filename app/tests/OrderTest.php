<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Orders;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OrderTest extends ApiTestCase
{
    private mixed $fake_data =[];
    private mixed $fake_response=[];


    public function setUp(): void
    {
        $this->fake_data = json_decode('{
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
        $this->fake_response =json_decode('{
  "@context": "/api/contexts/Orders",
  "@type": "Orders",
  "hash": "hash",
  "userId": 1,
  "token": "token_token",
  "number": "#A123123",
  "status": 1
}', true);
        self::bootKernel();
    }

    public function testExistApiDoc(): void
    {
        try {
            static::createClient()->request('GET', '/api/doc');
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
//Эндпоинт №3
    public function testCreateOrder(): void
    {
        $response = static::createClient()->request('POST', 'http://localhost:8080/api/orders', ['json' => $this->fake_data,
            'headers' => [
                'Content-Type' => 'application/ld+json', // Заголовок для тела запроса
                'Accept' => 'application/ld+json',       // Заголовок для формата ответа
            ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains($this->fake_response);
        $this->assertMatchesRegularExpression('~^/api/orders/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Orders::class);
    }
//Эндпоинт №3
    public function testFailedCreateOrder(): void
    {
        $bad_data = $this->fake_data;
        unset($bad_data['hash']);
        try {
            static::createClient()->request('POST', 'http://localhost:8080/api/orders', ['json' => $bad_data,
                'headers' => [
                    'Content-Type' => 'application/ld+json', // Заголовок для тела запроса
                    'Accept' => 'application/ld+json',       // Заголовок для формата ответа
                ]]);
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        $this->assertResponseStatusCodeSame(422);

        $this->assertJsonContains(
            ["violations" => [
                ["message" => "This value should not be blank."],
            ]]);
     }
//Эндпоинт №4
    public function testOrderFound(): void
    {
        try {
            static::createClient()->request('GET', 'http://localhost:8080/api/orders/1');
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesResourceItemJsonSchema(Orders::class);
    }
    //Эндпоинт №4
    public function testOrderNotFound(): void
    {
        try {
            static::createClient()->request('GET', 'http://localhost:8080/api/orders/1234');
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        $this->assertResponseStatusCodeSame(404);
    }
//Эндпоинт №2
    public function testOrdersCountSuccessfulResponse(): void
    {
        $client = static::createClient();


        try {
            $client->request('GET', 'http://localhost:8080/api/orders-count?page=1&limit=10&groupBy=day', [
                'headers' => [
                    'Accept' => 'application/ld+json',       // Заголовок для формата ответа
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }

        // Проверяем статус ответа
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        // Проверяем содержимое ответа
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('groupBy', $responseContent);
        $this->assertEquals('day', $responseContent['groupBy']);
        $this->assertArrayHasKey('totalPages', $responseContent);
        $this->assertArrayHasKey('page', $responseContent);
        $this->assertArrayHasKey('limit', $responseContent);
        $this->assertArrayHasKey('data', $responseContent);

        // Проверяем пример данных
        $this->assertIsArray($responseContent['data']);
        foreach ($responseContent['data'] as $dataItem) {
            $this->assertArrayHasKey('groupDate', $dataItem);
            $this->assertArrayHasKey('orderCount', $dataItem);
            $this->assertIsString($dataItem['groupDate']);
            $this->assertIsInt($dataItem['orderCount']);
        }
    }
//Эндпоинт №2
    public function testOrdersCountInvalidParameters(): void
    {
        $client = static::createClient();

        // Выполняем запрос с некорректным параметром groupBy
        try {
            $client->request('GET', 'http://localhost:8080/api/orders-count?page=1&limit=10&groupBy=noday', [
                'headers' => [
                    'Accept' => 'application/ld+json',       // Заголовок для формата ответа
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }

        // Проверяем статус ответа
        $this->assertResponseStatusCodeSame(400);

    }
//Эндпоинт №2
    public function testOrdersCountMissingRequiredParameter(): void
    {
        $client = static::createClient();

        // Выполняем запрос без обязательного параметра groupBy
        try {
            $client->request('GET', 'http://localhost:8080/api/orders-count?page=1&limit=&groupBy=', [
                'headers' => [
                    'Accept' => 'application/ld+json',       // Заголовок для формата ответа
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        // Проверяем статус ответа
        $this->assertResponseStatusCodeSame(400);
    }
    //Эндпоинт №4+
    public function testOrderSearchSuccessfulResponse(): void
    {
        $client = static::createClient();


        try {
            $client->request('GET', 'http://localhost:8080/api/search?term=%D0%A3%D0%B6%D0%B3%D0%BE%D1%80%D0%BE%D0%B4&page=0&limit=10&field_weights=name', [
                'headers' => [
                    'Accept' => 'application/ld+json',       // Заголовок для формата ответа
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }

        // Проверяем статус ответа
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        // Проверяем содержимое ответа
        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('success', $responseContent);
        $this->assertTrue($responseContent['success']);
        $this->assertArrayHasKey('total', $responseContent);
        $this->assertArrayHasKey('data', $responseContent);

        // Проверяем пример данных
        $this->assertIsArray($responseContent['data']);
        if(!empty($responseContent['data'])){
        foreach ($responseContent['data'] as $dataItem) {
            $this->assertArrayHasKey('id', $dataItem);
            $this->assertIsInt($dataItem['id']);
            $this->assertArrayHasKey('number', $dataItem);
            $this->assertIsString($dataItem['number']);
            $this->assertArrayHasKey('url', $dataItem);
            $this->assertIsString($dataItem['url']);
        }
        }
    }
    //Эндпоинт №4+
    public function testOrderSearchMissingResponse(): void
    {
        $client = static::createClient();
        try {
            $client->request('GET', 'http://localhost:8080/api/search?term=%D0%A3%D0%B6%D0%B3%D0%BE%D1%80%D0%BE%D0%B4&page=0&limit=10&field_weights=nameaaa', [
                'headers' => [
                    'Accept' => 'application/ld+json',       // Заголовок для формата ответа
                ]
            ]);
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        // Проверяем статус ответа
        $this->assertResponseStatusCodeSame(400);
        // Проверяем содержимое ответа
    }
}
