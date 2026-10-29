<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;
use Psr\Http\Message\ResponseInterface;

final class Product extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    public function get(string $shopId, array $params = []): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products", $params);
        return $this->httpLayer->get($uri);
    }

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

    /**
     * @param string|int $productId
     * @return array<string,mixed>
     */
    public function delete(string $shopId, $productId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}");
        return $this->httpLayer->delete($uri);
    }

    /**
     * @param string|int $productId
     * @return array<string,mixed>
     */
    public function changeStatus(string $shopId, $productId, bool $enabled): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}/change-status");
        return $this->httpLayer->put($uri, ['status' => $enabled]);
    }

    /**
     * @param string|int $productId
     * @param array<int,string|int> $categoryIds
     * @return array<string,mixed>
     */
    public function assignCategories(string $shopId, $productId, array $categoryIds, bool $replace = false): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}/categories/multiple");
        return $this->httpLayer->put($uri, ['categories' => $categoryIds, 'replace' => $replace]);
    }
}
