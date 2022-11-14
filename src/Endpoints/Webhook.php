<?php

namespace MailerLite\Endpoints;

class Webhook extends AbstractEndpoint
{
    protected string $endpoint = 'webhooks';

    public function create(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri($this->endpoint),
            $params
        );
    }

    public function find(string $webhookId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$webhookId}"
        );
    }

    public function get(array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    public function update(string $webhookId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$webhookId}",
            $params
        );
    }

    public function delete(string $webhookId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$webhookId}")
        );
    }
}
