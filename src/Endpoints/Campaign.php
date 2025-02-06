<?php

namespace MailerLite\Endpoints;

class Campaign extends AbstractEndpoint
{
    protected string $endpoint = 'campaigns';

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function create(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri($this->endpoint),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function find(string $campaignId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$campaignId}"
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function get(array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function update(string $campaignId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$campaignId}",
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $campaignId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$campaignId}")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function schedule(string $campaignId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$campaignId}/schedule"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $campaignId): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$campaignId}/cancel"),
            []
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getSubscriberActivity(string $campaignId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$campaignId}/reports/subscriber-activity"),
            $params
        );
    }
}
