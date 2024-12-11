<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PriceTest extends ApiTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetPrice(): void
    {
        try {
            static::createClient()->request('GET', 'http://' . $_ENV['DOMAIN'] . ':' . $_ENV['NGINX_PORT'] .'/api/price?factory=cobsa&collection=manual&article=manu7530bcbm-manualbaltic7-5x30');
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['price'=>38.99,'factory' => 'cobsa','collection'=>'manual','article'=>'manu7530bcbm-manualbaltic7-5x30']);
    }


    public function testGetPriceMisssing(): void
    {
        try {
            static::createClient()->request('GET', 'http://' . $_ENV['DOMAIN'] . ':' . $_ENV['NGINX_PORT'] .'/api/price?factory=cobsa');
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        $this->assertResponseStatusCodeSame(400);
    }
    public function testGetPriceNotFound(): void
    {
        try {
            static::createClient()->request('GET', 'http://' . $_ENV['DOMAIN'] . ':' . $_ENV['NGINX_PORT'] .'/api/price?factory=cobsa&collection=manual1&article=manu7530bcbm-manualbaltic7-5x30');
        } catch (TransportExceptionInterface $e) {
            $this->fail('Request failed: ' . $e->getMessage());
        }
        $this->assertResponseStatusCodeSame(404);
    }


}
