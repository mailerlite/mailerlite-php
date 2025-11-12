<?php

declare(strict_types=1);

namespace MailerLite\Tests\Endpoints;

use MailerLite\Endpoints\Cart;
use MailerLite\Tests\Support\PsrTestHelper;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    use PsrTestHelper;

    public function test_find_ok(): void
    {
        [$bridge, $options, $psr18] = $this->makeBridgeAndMock();

        $payload = [
            'id' => 'cart_123',
            'currency' => 'USD',
            'total' => 42.50,
            'items' => [
                ['sku' => 'SKU-1', 'qty' => 1],
            ],
        ];
        $psr18->addResponse(new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($payload, JSON_THROW_ON_ERROR)
        ));

        $endpoint = new Cart($bridge, $options);
        $res = $endpoint->find('shop_1', 'cart_123');

        $this->assertSame(200, $res['status_code']);
        $this->assertSame('cart_123', $res['body']['id']);
        $this->assertSame('USD', $res['body']['currency']);
    }
}
