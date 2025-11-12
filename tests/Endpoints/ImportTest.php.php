<?php

namespace MailerLite\Tests\Endpoints;

use MailerLite\Endpoints\Import;
use MailerLite\Tests\Support\PsrTestHelper;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ImportTest extends TestCase
{
    use PsrTestHelper;

    public function test_orders_import_ok(): void
    {
        [$bridge, $options, $psr18] = $this->makeBridgeAndMock();

        $payload = ['status' => 'accepted', 'import_id' => 'imp_1'];
        $psr18->addResponse(new Response(
            202,
            ['Content-Type' => 'application/json'],
            json_encode($payload, JSON_THROW_ON_ERROR)
        ));

        $endpoint = new Import($bridge, $options);
        $res = $endpoint->orders('shop_1', ['orders' => [/* ... */]]);

        $this->assertSame(202, $res['status_code']);
        $this->assertSame('imp_1', $res['body']['import_id']);
    }
}
