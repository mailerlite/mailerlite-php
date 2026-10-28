<?php

namespace MailerLite\Endpoints;

final class Shop extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    public function get(array $params = []): array
    {
        $uri = $this->buildUri('ecommerce/shops', $params);
        return $this->httpLayer->get($uri);
    }

    /**
     * @param string|int $shopId
     * @return array<string,mixed>
     */
    public function find($shopId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}");
        return $this->httpLayer->get($uri);
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        $uri = $this->buildUri('ecommerce/shops');
        return $this->httpLayer->post($uri, $data);
    }

    /**
     * @param string|int $shopId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update($shopId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}");
        return $this->httpLayer->put($uri, $data);
    }

    /**
     * @param string|int $shopId
     * @return array<string,mixed>
     */
    public function delete($shopId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}");
        return $this->httpLayer->delete($uri);
    }

    /**
     * @param string|int $shopId
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    public function stats($shopId, array $params): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/reports/stats", $params);
        return $this->httpLayer->get($uri);
    }
}
