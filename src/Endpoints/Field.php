<?php

namespace MailerLite\Endpoints;

class Field extends AbstractEndpoint
{
    protected string $endpoint = 'fields';

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
    public function update(string $fieldId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$fieldId}",
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $fieldId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$fieldId}")
        );
    }
}
