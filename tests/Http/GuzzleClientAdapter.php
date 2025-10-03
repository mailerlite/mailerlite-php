<?php

namespace MailerLite\Tests\Http;

use MailerLite\Http\Adapters\GuzzleClientAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as Psr18Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class GuzzleClientAdapterTest extends TestCase
{
    public function test_adapter_delegates_to_inner_psr18(): void
    {
        $psr17 = new Psr17Factory();
        $fakeResponse = new \GuzzleHttp\Psr7\Response(200, [], 'ok');

        $inner = new class($fakeResponse) implements Psr18Client {
            public function __construct(private ResponseInterface $resp)
            {
            }
            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                return $this->resp;
            }
        };

        $adapter = new GuzzleClientAdapter($inner);

        $req = $psr17->createRequest('GET', 'https://x');
        $resp = $adapter->sendRequest($req);

        $this->assertSame(200, $resp->getStatusCode());
        $this->assertSame('ok', (string)$resp->getBody());
    }
}
