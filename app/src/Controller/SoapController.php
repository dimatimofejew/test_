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
        // Настройка SOAP-сервера
        $wsdlPath = $_SERVER['DOCUMENT_ROOT'] . '/soap/soap.wsdl';
        $soapServer = new \SoapServer($wsdlPath);
        $soapServer->setObject($this->orderService);
        ob_start();
        $soapServer->handle();
        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');
        $response->setContent(ob_get_clean());
        return $response;
    }
}
