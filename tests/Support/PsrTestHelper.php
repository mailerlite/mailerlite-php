<?php

namespace MailerLite\Tests\Support;

use Http\Mock\Client as Psr18MockClient;
use MailerLite\Common\HttpLayerPsr;
use MailerLite\Common\HttpLayerPsrBridge;
use MailerLite\Http\Adapters\GuzzleClientAdapter;
use MailerLite\Http\Adapters\Psr17FactoryAggregate;
use Nyholm\Psr7\Factory\Psr17Factory;

trait PsrTestHelper
{
    /** @return array{0:HttpLayerPsrBridge,1:array<string,mixed>,2:Psr18MockClient} */
    private function makeBridgeAndMock(): array
    {
        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $options = [
            'protocol' => 'https',
            'host'     => 'connect.mailerlite.com',
            'api_path' => 'api',
            'api_key'  => 'TEST_KEY',
        ];

        $psr18 = new Psr18MockClient();
        $client = new GuzzleClientAdapter($psr18);

        $psrLayer = new HttpLayerPsr($client, $factories, 'TEST_KEY', 'https://connect.mailerlite.com');
        $bridge   = new HttpLayerPsrBridge($psrLayer);

        return [$bridge, $options, $psr18];
    }
}
