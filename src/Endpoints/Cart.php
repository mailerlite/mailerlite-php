<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class Cart extends AbstractEndpoint
{
    /**
     * @return array<string,mixed>
     */
    public function find(string $shopId, string|int $cartId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}");
        return $this->httpLayer->get($uri);
    }
}
