<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class Import extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function orders(string $shopId, array $payload): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/orders/import");
        return $this->httpLayer->post($uri, $payload);
    }

    /**
     * @param array<int,array<string,mixed>> $products
     * @return array<string,mixed>
     */
    public function products(string $shopId, array $products): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/import");
        return $this->httpLayer->post($uri, $products);
    }

    /**
     * @param array<int,array<string,mixed>> $categories
     * @return array<string,mixed>
     */
    public function categories(string $shopId, array $categories): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories/import");
        return $this->httpLayer->post($uri, $categories);
    }

    /**
     * @param array<int,array<string,mixed>> $customers
     * @return array<string,mixed>
     */
    public function customers(string $shopId, array $customers): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/customers/import");
        return $this->httpLayer->post($uri, $customers);
    }
}
