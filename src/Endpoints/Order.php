<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class Order extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function create(string $shopId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/orders");
        return $this->httpLayer->post($uri, $data);
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $shopId, string|int $orderId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/orders/{$orderId}");
        return $this->httpLayer->put($uri, $data);
    }

    /**
     * @return array<string,mixed>
     */
    public function find(string $shopId, string|int $orderId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/orders/{$orderId}");
        return $this->httpLayer->get($uri);
    }
}
