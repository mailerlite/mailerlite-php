<?php

namespace MailerLite\Tests\Http;

use MailerLite\Http\Adapters\Psr17FactoryAggregate;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

final class Psr17FactoryAggregateTest extends TestCase
{
    public function test_creates_request_with_headers_and_body(): void
    {
        $psr17 = new Psr17Factory();
        $agg = new Psr17FactoryAggregate($psr17, $psr17);

        $req = $agg->create(
            'POST',
            'https://example.test/api',
            ['X-Foo' => ['A', 'B'], 'Content-Type' => 'application/json'],
            '{"a":1}'
        );

        $this->assertSame('POST', $req->getMethod());
        $this->assertSame(['A','B'], $req->getHeader('X-Foo'));
        $this->assertSame('application/json', $req->getHeaderLine('Content-Type'));
        $this->assertSame('{"a":1}', (string)$req->getBody());
    }
}
