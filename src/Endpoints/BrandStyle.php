<?php

namespace MailerLite\Endpoints;

class BrandStyle extends AbstractEndpoint
{
    protected string $endpoint = 'brand-styles';

    /**
     * @return array<string, mixed>
     */
    public function get(): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint)
        );
    }
}
