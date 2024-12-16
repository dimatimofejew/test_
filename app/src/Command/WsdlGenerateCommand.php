<?php

namespace App\Command;

use App\Entity\Orders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


use App\Entity\Managers;
use App\Entity\Carriers;
use App\Entity\Clients;
use App\Entity\Addresses;
use App\Entity\Warehouses;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:wsdl-generate',
    description: 'Создаем файл конфигурации для soap сервера как-то так php bin/console app:wsdl-generate > ../public/soap.wsdl',
)]
class WsdlGenerateCommand extends Command
{
    private $entityManager;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }


        $this->generateXsdSchema();


        return Command::SUCCESS;
    }


    public function generateXsdSchema()
    {
        // Указываем класс, который нужно обработать
        $className = Orders::class;

        try {


            $metadata = $this->entityManager->getClassMetadata($className);
            $fieldMappings = $metadata->fieldMappings;

            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><wsdl:definitions name="Orders" targetNamespace="urn:Orders"  xmlns:tns="urn:Orders" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:xsd="http://www.w3.org/2001/XMLSchema"  xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns="http://schemas.xmlsoap.org/wsdl/"></wsdl:definitions>');
            //  $xml->addAttribute('name',"Orders");


            $types = $xml->addChild('wsdl:types');
            $schema = $types->addChild('xsd:schema', null, 'http://www.w3.org/2001/XMLSchema');
            $schema->addAttribute('targetNamespace', 'urn:Orders');

            $orderElement = $schema->addChild('xsd:element');
            $orderElement->addAttribute('name', 'order');
            $complexType = $orderElement->addChild('xsd:complexType');
            $sequence = $complexType->addChild('xsd:sequence');
            $orderwsdlResponse = $schema->addChild('element', null,);
            $orderwsdlResponse->addAttribute('name', 'orderwsdlResponse');
            $annotation = $orderwsdlResponse->addChild('annotation');
            $annotation->addChild('documentation', 'Response: Error or Success');
            $el = $orderwsdlResponse->addChild('complexType')->addChild('sequence')->addChild('element');
            $el->addAttribute('name', 'result');
            $el->addAttribute('type', 'xsd:string');
            //добавляем стандартные поля чтоб не пропустить (собственно для этого и мастерились эти грабли)
            foreach ($fieldMappings as $field => $mapping) {
                if (!in_array($mapping->fieldName, ['id', 'clientId'])) {
                    $propertyName = $mapping->fieldName;
                    $xsdType = $this->mapPhpTypeToXsdType($mapping->type);
                    if ($xsdType) {
                        $ell = $sequence->addChild('xsd:element', null,);
                        $ell->addAttribute('name', $propertyName);
                        $ell->addAttribute('type', $xsdType);
                        if ($mapping->nullable) {
                            $ell->addAttribute('nillable', 'true');
                        }
                    }
                }
            }
            //Добавляем поля ManyToOne
            $joinFilds = array(
                "manager" => "Managers",
                "client" => "Clients",
                "carrier" => "Carriers",
                "warehouse" => "Warehouses",
                "deliveryAddr" => "Addresses"
            );


            foreach ($joinFilds as $name => $class) {
                $ell = $sequence->addChild('xsd:element', null,);
                $ell->addAttribute('name', $name);
                $ell->addAttribute('type', 'tns:' . $class);
                $className = 'App\\Entity\\' . $class;
                $metadata = $this->entityManager->getClassMetadata($className);
                $fieldMappings = $metadata->fieldMappings;
                $complexType = $schema->addChild('xsd:complexType');
                $complexType->addAttribute('name', $class);
                $sequence1 = $complexType->addChild('xsd:sequence');
                foreach ($fieldMappings as $field => $mapping) {
                    if (!in_array($mapping->fieldName, ['id', 'clientId'])) {
                        $propertyName = $mapping->fieldName;
                        $xsdType = $this->mapPhpTypeToXsdType($mapping->type);
                        if ($xsdType) {
                            $ell = $sequence1->addChild('xsd:element', null,);
                            $ell->addAttribute('name', $propertyName);
                            $ell->addAttribute('type', $xsdType);
                            if ($mapping->nullable) {
                                $ell->addAttribute('nillable', 'true');
                            }

                        }
                    }
                }
            }
            //чтоб не добавлять схему другую выносим сюда //tns:Addresses
            $ell = $sequence->addChild('xsd:element', null,);
            $ell->addAttribute('name', 'bilingAddr');
            $ell->addAttribute('type', 'tns:Addresses');
            $message = $xml->addChild('wsdl:message');
            $message->addAttribute('name', 'OrderRequest');
            $part = $message->addChild('wsdl:part');
            $part->addAttribute('element', 'tns:order');
            $part->addAttribute('name', 'parameters');
            $message = $xml->addChild('wsdl:message');
            $message->addAttribute('name', 'OrderResponse');
            $part = $message->addChild('wsdl:part');
            $part->addAttribute('type', 'xsd:string');
            $part->addAttribute('name', 'result');
            $portType = $xml->addChild('wsdl:portType');
            $portType->addAttribute('name', 'OrderPort');
            $operation = $portType->addChild('wsdl:operation');
            $operation->addAttribute('name', 'order');
            $operation->addChild('input', null, null)->addAttribute('message', 'tns:OrderRequest');
            $operation->addChild('output', null, null)->addAttribute('message', 'tns:OrderResponse');
            $binding = $xml->addChild('wsdl:binding');
            $binding->addAttribute('name', 'OrderBinding');
            $binding->addAttribute('type', 'tns:OrderPort');
            $soap_binding = $binding->addChild('soap:binding', null, 'http://schemas.xmlsoap.org/wsdl/soap/');
            $soap_binding->addAttribute('style', 'rpc');
            $soap_binding->addAttribute('transport', 'http://schemas.xmlsoap.org/soap/http');
            $wsdl_operation = $binding->addChild('wsdl:operation', null, 'http://schemas.xmlsoap.org/wsdl/');
            $wsdl_operation->addAttribute('name', 'order');
            $soap_operation = $wsdl_operation->addChild('soap:operation', null, 'http://schemas.xmlsoap.org/wsdl/soap/');
            $soap_operation->addAttribute('soapAction', 'urn:OrderAction');
            $wsdl_input = $wsdl_operation->addChild('wsdl:input');
            $soap_body_input = $wsdl_input->addChild('soap:body', null, 'http://schemas.xmlsoap.org/wsdl/soap/');
            $soap_body_input->addAttribute('use', 'literal');
            $soap_body_input->addAttribute('namespace', 'urn:Orders');
            $soap_body_input->addAttribute('encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');
            $wsdl_output = $wsdl_operation->addChild('wsdl:output');
            $soap_body_output = $wsdl_output->addChild('soap:body', null, 'http://schemas.xmlsoap.org/wsdl/soap/');
            $soap_body_output->addAttribute('use', 'literal');
            $soap_body_output->addAttribute('namespace', 'urn:Orders');
            $soap_body_output->addAttribute('encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');

            $service = $xml->addChild('wsdl:service');
            $service->addAttribute('name', 'WSDLService');
            $port = $service->addChild('wsdl:port');
            $port->addAttribute('name', 'OrderPort');
            $port->addAttribute('binding', 'tns:OrderBinding');

            $address = $port->addChild('soap:address', null, 'http://schemas.xmlsoap.org/wsdl/soap/');

            $address->addAttribute('location', 'http://' . $_ENV['DOMAIN'] . ':' . $_ENV['NGINX_PORT'] . $this->urlGenerator->generate('app_soap_handlesoap', []));

            echo $xml->asXML();
            return;

        } catch (\ReflectionException $e) {
            throw new \RuntimeException('Error generating XSD schema: ' . $e->getMessage());
        }
    }

    /**
     * Преобразует PHP-тип в XSD-тип.
     */
    private function mapPhpTypeToXsdType(?string $phpType): ?string
    {
        return match ($phpType) {
            'integer' => 'xsd:integer',
            'smallint' => 'xsd:short',
            'float', 'double' => 'xsd:float',
            'string' => 'xsd:string',
            'text' => 'xsd:string',
            'bool', 'boolean' => 'xsd:boolean',
            'array' => 'xsd:array', // Если массив, можно уточнить
            'date' => 'xsd:dateTime',
            'datetime' => 'xsd:dateTime',
            default => null, // Неизвестный тип
        };
    }


    /**
     * Проверяет, является ли свойство nullable.
     */
    private function isNullable(?\ReflectionType $type): bool
    {

        // Если тип nullable, то проверяем, может ли он быть null (например, тип ?string или ?int)
        if ($type && $type->allowsNull()) {
            return true;
        }

        return false;
    }
}
