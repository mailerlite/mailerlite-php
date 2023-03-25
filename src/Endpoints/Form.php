<?php

namespace MailerLite\Endpoints;

class Form extends AbstractEndpoint
{
    protected string $endpoint = 'forms';

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function get(string $type, array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint . "/{$type}", $params)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function find(string $formId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint . "/{$formId}")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function update(string $formId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$formId}",
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $formId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$formId}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getSignups(string $formId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("filter[form]={$formId}")
        );
    }
}
