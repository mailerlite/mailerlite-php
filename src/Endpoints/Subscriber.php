<?php

namespace MailerLite\Endpoints;

class Subscriber extends AbstractEndpoint
{
    protected string $endpoint = 'subscribers';

    public function create(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri($this->endpoint),
            $params
        );
    }

    public function find(string $subscriberId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$subscriberId}"
        );
    }

    public function get(array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    public function update(string $subscriberId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$subscriberId}",
            $params
        );
    }

    public function delete(string $subscriberId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$subscriberId}")
        );
    }
}
