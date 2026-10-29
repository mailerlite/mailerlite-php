<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\MailerLite;
use MailerLite\Common\HttpLayer;
use MailerLite\Tests\TestCase;

class EcommerceTest extends TestCase
{
    /**
     * @dataProvider requests
     * @param array<string,mixed> $query
     * @param array<mixed>|null $body
     */
    public function test_request(callable $call, string $method, string $path, array $query = [], ?array $body = null)
    {
        $this->client = new Client();
        $call(new MailerLite(self::OPTIONS, new HttpLayer(self::OPTIONS, $this->client)));

        $request = $this->client->getLastRequest();
        parse_str($request->getUri()->getQuery(), $actualQuery);

        self::assertEquals($method, $request->getMethod());
        self::assertEquals($path, $request->getUri()->getPath());
        self::assertEquals($query, $actualQuery);
        if ($body !== null) {
            self::assertSame($body, json_decode((string) $request->getBody(), true));
        }
    }

    public function requests(): array
    {
        $shop = '/api/ecommerce/shops/1';
        $variant = ['name' => 'Large', 'attributes' => ['size' => 'L']];

        return [
            'shops get' => [fn (MailerLite $ml) => $ml->ecommerceShops->get(), 'GET', '/api/ecommerce/shops'],
            'shops find' => [fn (MailerLite $ml) => $ml->ecommerceShops->find('1'), 'GET', $shop],
            'shops create' => [fn (MailerLite $ml) => $ml->ecommerceShops->create(['name' => 'Shop']), 'POST', '/api/ecommerce/shops', [], ['name' => 'Shop']],
            'shops update' => [fn (MailerLite $ml) => $ml->ecommerceShops->update('1', ['name' => 'New']), 'PUT', $shop, [], ['name' => 'New']],
            'shops delete' => [fn (MailerLite $ml) => $ml->ecommerceShops->delete('1'), 'DELETE', $shop],
            'shops stats' => [
                fn (MailerLite $ml) => $ml->ecommerceShops->stats('1', ['start_date' => '2026-10-01', 'end_date' => '2026-10-31']),
                'GET',
                "$shop/reports/stats",
                ['start_date' => '2026-10-01', 'end_date' => '2026-10-31'],
            ],
            'settings get' => [fn (MailerLite $ml) => $ml->ecommerceSettings->get(), 'GET', '/api/ecommerce/settings'],

            'products get' => [
                fn (MailerLite $ml) => $ml->ecommerceProducts->get('1', ['filter' => ['enabled' => 'true'], 'sort' => '-name']),
                'GET',
                "$shop/products",
                ['filter' => ['enabled' => 'true'], 'sort' => '-name'],
            ],
            'products delete' => [fn (MailerLite $ml) => $ml->ecommerceProducts->delete('1', '7'), 'DELETE', "$shop/products/7"],
            'products change status' => [
                fn (MailerLite $ml) => $ml->ecommerceProducts->changeStatus('1', '7', false),
                'PUT',
                "$shop/products/7/change-status",
                [],
                ['status' => false],
            ],
            'products assign categories' => [
                fn (MailerLite $ml) => $ml->ecommerceProducts->assignCategories('1', '7', ['5'], true),
                'PUT',
                "$shop/products/7/categories/multiple",
                [],
                ['categories' => ['5'], 'replace' => true],
            ],

            'variants get' => [fn (MailerLite $ml) => $ml->ecommerceProductVariants->get('1', '7'), 'GET', "$shop/products/7/variants"],
            'variants sync' => [
                fn (MailerLite $ml) => $ml->ecommerceProductVariants->sync('1', '7', [$variant]),
                'POST',
                "$shop/products/7/variants",
                [],
                [$variant],
            ],
            'variants update' => [
                fn (MailerLite $ml) => $ml->ecommerceProductVariants->update('1', '7', '3', $variant),
                'PUT',
                "$shop/products/7/variants/3",
                [],
                $variant,
            ],
            'variants delete' => [fn (MailerLite $ml) => $ml->ecommerceProductVariants->delete('1', '7', '3'), 'DELETE', "$shop/products/7/variants/3"],

            'categories get' => [fn (MailerLite $ml) => $ml->ecommerceCategories->get('1'), 'GET', "$shop/categories"],
            'categories find' => [fn (MailerLite $ml) => $ml->ecommerceCategories->find('1', '5'), 'GET', "$shop/categories/5"],
            'categories create' => [fn (MailerLite $ml) => $ml->ecommerceCategories->create('1', ['name' => 'Hats']), 'POST', "$shop/categories", [], ['name' => 'Hats']],
            'categories update' => [fn (MailerLite $ml) => $ml->ecommerceCategories->update('1', '5', ['name' => 'Caps']), 'PUT', "$shop/categories/5", [], ['name' => 'Caps']],
            'categories delete' => [fn (MailerLite $ml) => $ml->ecommerceCategories->delete('1', '5'), 'DELETE', "$shop/categories/5"],
            'categories products' => [fn (MailerLite $ml) => $ml->ecommerceCategories->products('1', '5'), 'GET', "$shop/categories/5/products"],
            'categories assign product' => [fn (MailerLite $ml) => $ml->ecommerceCategories->assignProduct('1', '5', '7'), 'PUT', "$shop/categories/5/products/7"],
            'categories unassign product' => [fn (MailerLite $ml) => $ml->ecommerceCategories->unassignProduct('1', '5', '7'), 'DELETE', "$shop/categories/5/products/7"],

            'customers update' => [fn (MailerLite $ml) => $ml->ecommerceCustomers->update('1', '3', ['name' => 'Jane']), 'PUT', "$shop/customers/3", [], ['name' => 'Jane']],
            'customers delete' => [fn (MailerLite $ml) => $ml->ecommerceCustomers->delete('1', '3'), 'DELETE', "$shop/customers/3"],

            'orders get' => [
                fn (MailerLite $ml) => $ml->ecommerceOrders->get('1', ['filter' => ['attributed' => 'true', 'email_id' => '55']]),
                'GET',
                "$shop/orders",
                ['filter' => ['attributed' => 'true', 'email_id' => '55']],
            ],
            'orders delete' => [fn (MailerLite $ml) => $ml->ecommerceOrders->delete('1', '9'), 'DELETE', "$shop/orders/9"],

            'carts get' => [fn (MailerLite $ml) => $ml->ecommerceCarts->get('1'), 'GET', "$shop/carts"],
            'carts update' => [fn (MailerLite $ml) => $ml->ecommerceCarts->update('1', '4', ['cart_total' => 10]), 'PUT', "$shop/carts/4", [], ['cart_total' => 10]],
            'cart items get' => [fn (MailerLite $ml) => $ml->ecommerceCartItems->get('1', '4'), 'GET', "$shop/carts/4/items"],
            'cart items find' => [fn (MailerLite $ml) => $ml->ecommerceCartItems->find('1', '4', '8'), 'GET', "$shop/carts/4/items/8"],
            'cart items update' => [fn (MailerLite $ml) => $ml->ecommerceCartItems->update('1', '4', '8', ['quantity' => 2]), 'PUT', "$shop/carts/4/items/8", [], ['quantity' => 2]],
            'cart items delete' => [fn (MailerLite $ml) => $ml->ecommerceCartItems->delete('1', '4', '8'), 'DELETE', "$shop/carts/4/items/8"],

            'import products' => [fn (MailerLite $ml) => $ml->ecommerceImport->products('1', [['name' => 'Hat']]), 'POST', "$shop/products/import", [], [['name' => 'Hat']]],
            'import categories' => [fn (MailerLite $ml) => $ml->ecommerceImport->categories('1', [['name' => 'Hats']]), 'POST', "$shop/categories/import", [], [['name' => 'Hats']]],
            'import customers' => [
                fn (MailerLite $ml) => $ml->ecommerceImport->customers('1', [['email' => 'user@example.com']]),
                'POST',
                "$shop/customers/import",
                [],
                [['email' => 'user@example.com']],
            ],
        ];
    }
}
