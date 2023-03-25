<?php

namespace MailerLite\Endpoints;

class Webhook extends AbstractEndpoint
{
    protected string $endpoint = 'webhooks';

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
    public function find(string $webhookId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$webhookId}"
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function get(array $params = []): array
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
    public function update(string $webhookId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$webhookId}",
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $webhookId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$webhookId}")
        );
    }
}
