<?php

namespace MailerLite\Tests\Endpoints;

use MailerLite\Endpoints\Customer;
use MailerLite\Tests\Support\PsrTestHelper;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class CustomerTest extends TestCase
{
    use PsrTestHelper;

    public function test_list_ok(): void
    {
        [$bridge, $options, $psr18] = $this->makeBridgeAndMock();

        $payload = [
            'data' => [
                ['id' => 'c1', 'email' => 'a@ex.com'],
                ['id' => 'c2', 'email' => 'b@ex.com'],
            ],
        ];
        $psr18->addResponse(new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($payload, JSON_THROW_ON_ERROR)
        ));

        $endpoint = new Customer($bridge, $options);

        $res = $endpoint->get('shop_1', ['limit' => 2]);

        $this->assertSame(200, $res['status_code']);
        $this->assertIsArray($res['body']);
        $this->assertCount(2, $res['body']['data']);
        $this->assertSame('c1', $res['body']['data'][0]['id']);
    }
}
