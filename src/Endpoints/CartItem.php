<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class CartItem extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function upsert(string $shopId, string|int $cartId, array $data): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}/items");
        return $this->httpLayer->post($uri, $data);
    }
}
