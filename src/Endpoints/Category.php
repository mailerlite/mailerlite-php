<?php

namespace MailerLite\Endpoints;

final class Category extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    public function get(string $shopId, array $params = []): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories", $params);
        return $this->httpLayer->get($uri);
    }

    /**
     * @param string|int $categoryId
     * @return array<string,mixed>
     */
    public function find(string $shopId, $categoryId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories/{$categoryId}");
        return $this->httpLayer->get($uri);
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function create(string $shopId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories");
        return $this->httpLayer->post($uri, $data);
    }

    /**
     * @param string|int $categoryId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $shopId, $categoryId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories/{$categoryId}");
        return $this->httpLayer->put($uri, $data);
    }

    /**
     * @param string|int $categoryId
     * @return array<string,mixed>
     */
    public function delete(string $shopId, $categoryId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories/{$categoryId}");
        return $this->httpLayer->delete($uri);
    }

    /**
     * @param string|int $categoryId
     * @param array<string,mixed> $params
     * @return array<string,mixed>
     */
    public function products(string $shopId, $categoryId, array $params = []): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories/{$categoryId}/products", $params);
        return $this->httpLayer->get($uri);
    }

    /**
     * @param string|int $categoryId
     * @param string|int $productId
     * @return array<string,mixed>
     */
    public function assignProduct(string $shopId, $categoryId, $productId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories/{$categoryId}/products/{$productId}");
        return $this->httpLayer->put($uri, []);
    }

    /**
     * @param string|int $categoryId
     * @param string|int $productId
     * @return array<string,mixed>
     */
    public function unassignProduct(string $shopId, $categoryId, $productId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/categories/{$categoryId}/products/{$productId}");
        return $this->httpLayer->delete($uri);
    }
}
