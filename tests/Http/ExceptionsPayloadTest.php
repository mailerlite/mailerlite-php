<?php

namespace MailerLite\Tests\Http;

use MailerLite\Http\Exceptions\Unauthorized;
use PHPUnit\Framework\TestCase;

final class ExceptionsPayloadTest extends TestCase
{
    public function test_http_exception_holds_status_body_headers(): void
    {
        $ex = new Unauthorized('msg', 401, 'body', ['X-Request-Id' => ['id-1']]);

        $this->assertSame(401, $ex->getStatusCode());
        $this->assertSame('body', $ex->getResponseBody());
        $this->assertSame(['X-Request-Id' => ['id-1']], $ex->getResponseHeaders());
        $this->assertSame('id-1', $ex->getRequestId());
    }
}
