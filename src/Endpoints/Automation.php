<?php

namespace MailerLite\Endpoints;

class Automation extends AbstractEndpoint
{
    protected string $endpoint = 'automations';

    public function find(string $automationId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$automationId}"
        );
    }

    public function get(array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    public function activity(string $automationId, array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint . "/{$automationId}/activity", $params)
        );
    }
}
