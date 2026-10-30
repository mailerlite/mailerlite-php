<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class CartItem extends AbstractEndpoint
{
    /**
     * @param string $shopId
     * @param string|int $cartId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function upsert($shopId, $cartId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}/items");
        return $this->httpLayer->post($uri, $data);
    }

    /**
     * @param string|int $cartId
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    public function get(string $shopId, $cartId, array $params = []): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}/items", $params);
        return $this->httpLayer->get($uri);
    }

    /**
     * @param string|int $cartId
     * @param string|int $itemId
     * @return array<string,mixed>
     */
    public function find(string $shopId, $cartId, $itemId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}/items/{$itemId}");
        return $this->httpLayer->get($uri);
    }

    /**
     * @param string|int $cartId
     * @param string|int $itemId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $shopId, $cartId, $itemId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}/items/{$itemId}");
        return $this->httpLayer->put($uri, $data);
    }

    /**
     * @param string|int $cartId
     * @param string|int $itemId
     * @return array<string,mixed>
     */
    public function delete(string $shopId, $cartId, $itemId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}/items/{$itemId}");
        return $this->httpLayer->delete($uri);
    }
}
