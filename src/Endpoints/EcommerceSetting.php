<?php

namespace MailerLite\Endpoints;

final class EcommerceSetting extends AbstractEndpoint
{
    /**
     * @return array<string,mixed>
     */
    public function get(): array
    {
        $uri = $this->buildUri('ecommerce/settings');
        return $this->httpLayer->get($uri);
    }
}
