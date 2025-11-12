<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client as Psr18MockClient;
use MailerLite\Common\HttpLayerPsr;
use MailerLite\Common\HttpLayerPsrBridge;
use MailerLite\Endpoints\Order;
use MailerLite\Http\Adapters\GuzzleClientAdapter;
use MailerLite\Http\Adapters\Psr17FactoryAggregate;
use MailerLite\Http\Exceptions\NotFound;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
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

    public function test_find_404_maps_to_NotFound(): void
    {
        [$factories, $options] = $this->makeBridge();

        $psr18 = new Psr18MockClient();
        $psr18->addResponse(new Response(
            404,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'order not found'], JSON_THROW_ON_ERROR)
        ));

        $client   = new GuzzleClientAdapter($psr18);
        $psrLayer = new HttpLayerPsr($client, $factories, 'TEST_KEY', 'https://connect.mailerlite.com');
        $bridge   = new HttpLayerPsrBridge($psrLayer);

        $endpoint = new Order($bridge, $options);

        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('order not found');

        $endpoint->find('shop_1', 'order_404');
    }
}
