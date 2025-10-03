<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class Cart extends AbstractEndpoint
{
    /**
      * @param string|int $cartId
      * @return array<string,mixed>
      */
    public function find(string $shopId, $cartId): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/carts/{$cartId}");
        return $this->httpLayer->get($uri);
    }
}
