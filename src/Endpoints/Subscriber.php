<?php

namespace MailerLite\Endpoints;

class Subscriber extends AbstractEndpoint
{
    protected string $endpoint = 'subscribers';

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
    public function find(string $subscriberId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$subscriberId}"
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
    public function update(string $subscriberId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$subscriberId}",
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $subscriberId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$subscriberId}")
        );
    }
}
