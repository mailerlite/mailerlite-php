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
}
