<?php

namespace MailerLite\Endpoints;

use MailerLite\Endpoints\AbstractEndpoint;

final class Import extends AbstractEndpoint
{
    /**
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function orders(string $shopId, array $payload): array
    {
        $uri = $this->buildUri("ecommerce/shops/{$shopId}/orders/import");
        return $this->httpLayer->post($uri, $payload);
    }
}
