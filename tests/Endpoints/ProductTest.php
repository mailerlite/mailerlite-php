<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client as Psr18MockClient;
use MailerLite\Common\HttpLayerPsr;
use MailerLite\Common\HttpLayerPsrBridge;
use MailerLite\Endpoints\Product;
use MailerLite\Http\Adapters\GuzzleClientAdapter;
use MailerLite\Http\Adapters\Psr17FactoryAggregate;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    private function makeBridge(): array
    {
        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $options = [
            'protocol' => 'https',
            'host'     => 'connect.mailerlite.com',
            'api_path' => 'api',
            'api_key'  => 'TEST_KEY',
        ];

        return [$factories, $options];
    }

    public function test_create_ok(): void
    {
        [$factories, $options] = $this->makeBridge();

        $payload = [
            'id'       => 321,
            'title'    => 'Red T-shirt',
            'price'    => 19.99,
            'currency' => 'USD',
        ];

        $psr18 = new Psr18MockClient();
        $psr18->addResponse(new Response(
            201,
            ['Content-Type' => 'application/json'],
            json_encode($payload, JSON_THROW_ON_ERROR)
        ));
        $client   = new GuzzleClientAdapter($psr18);
        $psrLayer = new HttpLayerPsr($client, $factories, 'TEST_KEY', 'https://connect.mailerlite.com');
        $bridge   = new HttpLayerPsrBridge($psrLayer);

        $endpoint = new Product($bridge, $options);

        $shopId = 'shop_1';
        $data   = ['title' => 'Red T-shirt', 'price' => 19.99, 'currency' => 'USD'];

        $res = $endpoint->create($shopId, $data);

        $this->assertSame(201, $res['status_code']);
        $this->assertIsArray($res['headers']);
        $this->assertIsArray($res['body']);
        $this->assertSame(321, $res['body']['id']);
        $this->assertSame('Red T-shirt', $res['body']['title']);
        $this->assertSame(19.99, $res['body']['price']);
    }
}
