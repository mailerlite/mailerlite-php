<?php

namespace MailerLite\Endpoints;

class Form extends AbstractEndpoint
{
    protected string $endpoint = 'forms';

    public function get(array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    public function update(string $formId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$formId}",
            $params
        );
    }

    public function delete(string $formId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$formId}")
        );
    }
}
