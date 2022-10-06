<?php

namespace MailerLite\Endpoints;

class Segment extends AbstractEndpoint
{
    protected string $endpoint = 'segments';

    public function get(array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    public function update(string $segmentId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$segmentId}",
            $params
        );
    }

    public function delete(string $segmentId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$segmentId}")
        );
    }

    public function getSubscribers(string $segmentId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$segmentId}/subscribers"
        );
    }
}
