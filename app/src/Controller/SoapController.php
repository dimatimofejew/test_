<?php

namespace App\Controller;


use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SoapController extends AbstractController
{
    private $orderService;


    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;

    }


    #[Route('/soap/orders', methods: ['POST', 'GET'])]
    public function handleSoap(Request $request): Response
    {


        ob_start();

        try {
            $wsdlPath = __DIR__.'/../../public/soap/soap.wsdl';
            $soapServer = new \SoapServer($wsdlPath);
            $soapServer->setObject($this->orderService);
            $soapServer->handle(); // Обрабатываем SOAP-запрос
        } catch (\Throwable $e) {
            // Ловим ошибки SOAP-сервера
            ob_end_clean(); // Закрываем текущий буфер в случае ошибки
            return new Response('SOAP Server Error: ' . $e->getMessage(), 500);
        }

        // Получаем содержимое SOAP-буфера
        $soapOutput = ob_get_clean();

        // Если был активный буфер вывода, восстанавливаем его
        $existingBufferActive = ob_get_level() > 0;
        $existingBufferContent = '';

        if ($existingBufferActive) {
            // Сохраняем текущий буфер и закрываем его
            $existingBufferContent = ob_get_clean();
        }
        if ($existingBufferActive) {
           // ob_start();
            $soapOutput.= $existingBufferContent;
        }

        // Формируем HTTP-ответ
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $response->setContent($soapOutput);

        return $response;
    }

    #[Route('/soaptest', methods: ['GET'])]
    public function testSoap(Request $request): Response
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
        $client = new \SoapClient(__DIR__.'/../../public/soap/soap.wsdl', array('exceptions' => 1));
        $client->__setLocation('http://nginx:8080/soap/orders');
        $result = $client->order($fake_data);
        if (is_soap_fault($result)) {
            trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
        }
        $respons =new Response();
        $respons->setContent($result);
        return $respons;

        //die();
    }

}
