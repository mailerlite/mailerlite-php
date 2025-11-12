<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;
use Psr\Http\Message\ResponseInterface;

final class Product extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function create(string $shopId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products");
        return $this->httpLayer->post($uri, $data);
    }

    /**
     * @param string|int $productId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $shopId, $productId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}");
        return $this->httpLayer->put($uri, $data);
    }

    /**
      * @param string|int $productId
      * @return array<string,mixed>
      */
    public function find(string $shopId, $productId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}");
        return $this->httpLayer->get($uri);
    }
}
