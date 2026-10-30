<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class Cart extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    public function get(string $shopId, array $params = []): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts", $params);
        return $this->httpLayer->get($uri);
    }

    /**
      * @param string|int $cartId
      * @return array<string,mixed>
      */
    public function find(string $shopId, $cartId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}");
        return $this->httpLayer->get($uri);
    }

    /**
     * @param string|int $cartId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $shopId, $cartId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}");
        return $this->httpLayer->put($uri, $data);
    }
}
