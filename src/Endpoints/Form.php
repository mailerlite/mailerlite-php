<?php

namespace MailerLite\Endpoints;

class Form extends AbstractEndpoint
{
    protected string $endpoint = 'forms';

    public function get(string $type, array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint . "/{$type}", $params)
        );
    }

    public function find(string $formId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint . "/{$formId}")
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

    public function getSignups(string $formId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("filter[form]={$formId}")
        );
    }
}
