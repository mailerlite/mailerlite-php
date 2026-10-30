<?php

namespace MailerLite\Tests\Common;

use GuzzleHttp\Psr7\Response;
use MailerLite\Common\HttpLayerPsr;
use MailerLite\Common\HttpLayerPsrBridge;
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

    public function test_patch_ok(): void
    {
        $fake = new FakeClient();
        $fake->queue = [ new Response(200, [], '{"ok":true}') ];

        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $layer = new HttpLayerPsr(
            $fake,
            $factories,
            'TEST_KEY'
        );

        $resp = $layer->patch('api/emails/123/settings', ['subject' => 'Hi']);

        $this->assertSame(200, $resp->getStatusCode());
        $this->assertSame('PATCH', $fake->lastRequest->getMethod());
        $this->assertSame('{"subject":"Hi"}', (string)$fake->lastRequest->getBody());
    }

    public function test_bridge_routes_patch(): void
    {
        $fake = new FakeClient();
        $fake->queue = [
            new Response(200, ['Content-Type' => 'application/json'], '{"ok":true}'),
            new Response(200, ['Content-Type' => 'application/json'], '{"ok":true}'),
        ];

        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $bridge = new HttpLayerPsrBridge(new HttpLayerPsr($fake, $factories, 'TEST_KEY'));

        $bridge->patch('api/emails/123/settings', ['subject' => 'Hi']);
        $this->assertSame('PATCH', $fake->lastRequest->getMethod());
        $this->assertSame('{"subject":"Hi"}', (string)$fake->lastRequest->getBody());

        $bridge->request('PATCH', 'api/emails/123/settings', '{"subject":"Hi"}');
        $this->assertSame('PATCH', $fake->lastRequest->getMethod());
        $this->assertSame('{"subject":"Hi"}', (string)$fake->lastRequest->getBody());
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

    public function test_bridge_forwards_delete_body_and_headers(): void
    {
        $fake = new FakeClient();
        $fake->queue = [
            new Response(200, ['Content-Type' => 'application/json', 'Idempotent-Replayed' => 'true'], '{"etag":"43"}'),
            new Response(200, ['Content-Type' => 'application/json'], '{"etag":"44"}'),
        ];

        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $bridge = new HttpLayerPsrBridge(new HttpLayerPsr($fake, $factories, 'TEST_KEY'));

        $resp = $bridge->delete('api/emails/123/blocks/456', ['etag' => '42'], ['Idempotency-Key' => 'k']);
        $this->assertSame('DELETE', $fake->lastRequest->getMethod());
        $this->assertSame('{"etag":"42"}', (string)$fake->lastRequest->getBody());
        $this->assertSame('k', $fake->lastRequest->getHeaderLine('Idempotency-Key'));
        $this->assertSame('Bearer TEST_KEY', $fake->lastRequest->getHeaderLine('Authorization'));
        $this->assertTrue($resp['is_idempotent_replay']);

        $resp = $bridge->request('DELETE', 'api/emails/123/blocks/456', '{"etag":"43"}');
        $this->assertSame('{"etag":"43"}', (string)$fake->lastRequest->getBody());
        $this->assertFalse($resp['is_idempotent_replay']);
    }

    public function test_bridge_post_without_body(): void
    {
        $fake = new FakeClient();
        $fake->queue = [ new Response(201, ['Content-Type' => 'application/json'], '{"data":{}}') ];

        $psr17 = new Psr17Factory();
        $factories = new Psr17FactoryAggregate($psr17, $psr17);

        $bridge = new HttpLayerPsrBridge(new HttpLayerPsr($fake, $factories, 'TEST_KEY'));

        $bridge->post('api/emails/123/versions', null);
        $this->assertSame('POST', $fake->lastRequest->getMethod());
        $this->assertSame('', (string)$fake->lastRequest->getBody());
    }
}
