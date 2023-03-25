<?php

namespace MailerLite\Endpoints;

class Segment extends AbstractEndpoint
{
    protected string $endpoint = 'segments';

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
    public function update(string $segmentId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$segmentId}",
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $segmentId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$segmentId}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getSubscribers(string $segmentId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$segmentId}/subscribers"
        );
    }
}
