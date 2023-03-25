<?php

namespace MailerLite\Endpoints;

class CampaignLanguage extends AbstractEndpoint
{
    protected string $endpoint = 'campaigns/languages';

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
