<?php

namespace MailerLite\Tests\Http;

use GuzzleHttp\Psr7\Response;
use MailerLite\Http\RetryingClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use MailerLite\Tests\Fakes\FakeClient;

final class RetryingClientTest extends TestCase
{
    public function test_retries_on_429_then_succeeds(): void
    {
        $fake = new FakeClient();
        $fake->queue = [
            new Response(429, ['Retry-After' => '0'], 'rate'),
            new Response(200, [], '{"ok":true}'),
        ];

        $client = new RetryingClient($fake, maxAttempts: 3, baseDelayMs: 1);
        $req = (new Psr17Factory())->createRequest('GET', 'https://x');

        $resp = $client->sendRequest($req);

        $this->assertSame(200, $resp->getStatusCode());
        $this->assertSame('{"ok":true}', (string)$resp->getBody());
    }

    public function test_retries_on_500_then_succeeds(): void
    {
        $fake = new FakeClient();
        $fake->queue = [
            new Response(500, [], 'err'),
            new Response(200, [], 'ok'),
        ];

        $client = new RetryingClient($fake, maxAttempts: 3, baseDelayMs: 1);
        $req = (new Psr17Factory())->createRequest('GET', 'https://x');

        $resp = $client->sendRequest($req);

        $this->assertSame(200, $resp->getStatusCode());
        $this->assertSame('ok', (string)$resp->getBody());
    }

    public function test_stops_after_max_attempts(): void
    {
        $fake = new FakeClient();
        $fake->queue = [
            new Response(500, [], 'e1'),
            new Response(500, [], 'e2'),
            new Response(500, [], 'e3'),
        ];

        $client = new RetryingClient($fake, maxAttempts: 3, baseDelayMs: 1);
        $req = (new Psr17Factory())->createRequest('GET', 'https://x');

        $resp = $client->sendRequest($req);

        $this->assertSame(500, $resp->getStatusCode());
        $this->assertSame('e3', (string)$resp->getBody());
    }
}
