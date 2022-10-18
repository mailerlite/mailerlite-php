<?php

namespace MailerLite\Endpoints;

class Campaign extends AbstractEndpoint
{
    protected string $endpoint = 'campaigns';

    public function create(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri($this->endpoint),
            $params
        );
    }

    public function find(string $campaignId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$campaignId}"
        );
    }

    public function get(array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    public function update(string $campaignId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$campaignId}",
            $params
        );
    }

    public function delete(string $campaignId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$campaignId}")
        );
    }

    public function schedule(string $campaignId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$campaignId}/schedule"),
            $params
        );
    }

    public function cancel(string $campaignId): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$campaignId}/cancel"),
            []
        );
    }

    public function getSubscriberActivity(string $campaignId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$campaignId}/subscriber-activity"),
            $params
        );
    }
}
