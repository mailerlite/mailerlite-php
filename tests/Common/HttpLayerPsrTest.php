<?php

namespace MailerLite\Tests\Common;

use GuzzleHttp\Psr7\Response;
use MailerLite\Common\HttpLayerPsr;
use MailerLite\Http\Adapters\Psr17FactoryAggregate;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use MailerLite\Tests\Fakes\FakeClient;

final class HttpLayerPsrTest extends TestCase
{
    public function test_post_ok(): void
    {
        $fake = new FakeClient();
        $fake->queue = [ new Response(200, [], '{"ok":true}') ];

        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $layer = new HttpLayerPsr(
            $fake,
            $factories,
            'TEST_KEY',
            'https://connect.mailerlite.com'
        );

        $resp = $layer->post('api/subscribers', ['email' => 'a@b.com']);

        $this->assertSame(200, $resp->getStatusCode());
        $this->assertSame('{"ok":true}', (string)$resp->getBody());
    }

    public function test_delete_404_maps_to_exception(): void
    {
        $this->expectException(\MailerLite\Http\Exceptions\NotFound::class);

        $fake = new FakeClient();
        $fake->queue = [ new Response(404, [], 'nope') ];

        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $layer = new HttpLayerPsr(
            $fake,
            $factories,
            'TEST_KEY'
        );

        $layer->delete('api/subscribers/123');
    }
}
