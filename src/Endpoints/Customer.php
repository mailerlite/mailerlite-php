<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class Customer extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $filter
     * @return array<string,mixed>
     */
    public function get(string $shopId, array $filter = []): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/customers", $filter);
        return $this->httpLayer->get($uri);
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function create(string $shopId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/customers");
        return $this->httpLayer->post($uri, $data);
    }

    /**
     * @return array<string,mixed>
     */
    public function find(string $shopId, string|int $customerId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/customers/{$customerId}");
        return $this->httpLayer->get($uri);
    }
}
