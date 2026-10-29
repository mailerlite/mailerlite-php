<?php

namespace MailerLite\Endpoints;

final class ProductVariant extends AbstractEndpoint
{
    /**
     * @param string|int $productId
     * @return array<string,mixed>
     */
    public function get(string $shopId, $productId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}/variants");
        return $this->httpLayer->get($uri);
    }

    /**
     * @param string|int $productId
     * @param array<int,array<string,mixed>> $variants
     * @return array<string,mixed>
     */
    public function sync(string $shopId, $productId, array $variants): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}/variants");
        return $this->httpLayer->post($uri, $variants);
    }

    /**
     * @param string|int $productId
     * @param string|int $variantId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $shopId, $productId, $variantId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}/variants/{$variantId}");
        return $this->httpLayer->put($uri, $data);
    }

    /**
     * @param string|int $productId
     * @param string|int $variantId
     * @return array<string,mixed>
     */
    public function delete(string $shopId, $productId, $variantId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/products/{$productId}/variants/{$variantId}");
        return $this->httpLayer->delete($uri);
    }
}
