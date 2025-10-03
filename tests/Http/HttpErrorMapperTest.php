<?php

namespace MailerLite\Tests\Http;

use GuzzleHttp\Psr7\Response;
use MailerLite\Http\HttpErrorMapper;
use MailerLite\Http\Exceptions as Ex;
use PHPUnit\Framework\TestCase;

final class HttpErrorMapperTest extends TestCase
{
    public function test_unauthorized_exception_carries_details(): void
    {
        $resp = new Response(401, ['X-Request-Id' => ['abc-123']], 'invalid');

        try {
            HttpErrorMapper::throwIfError($resp);
            $this->fail('No exception thrown');
        } catch (Ex\Unauthorized $e) {
            $this->assertSame(401, $e->getStatusCode());
            $this->assertSame('invalid', $e->getResponseBody());
            $this->assertSame('abc-123', $e->getRequestId());
        }
    }

    public function test_not_found(): void
    {
        $this->expectException(Ex\NotFound::class);
        HttpErrorMapper::throwIfError(new Response(404, [], 'nope'));
    }

    public function test_too_many_requests(): void
    {
        $this->expectException(Ex\TooManyRequests::class);
        HttpErrorMapper::throwIfError(new Response(429, ['Retry-After' => ['2']], 'rl'));
    }

    public function test_server_error(): void
    {
        $this->expectException(Ex\ServerError::class);
        HttpErrorMapper::throwIfError(new Response(503, [], 'maintenance'));
    }

    public function test_ok_no_exception(): void
    {
        HttpErrorMapper::throwIfError(new Response(200, [], 'ok'));
        $this->assertTrue(true);
    }
}
