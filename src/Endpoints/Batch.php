<?php

namespace MailerLite\Endpoints;

class Batch extends AbstractEndpoint
{
    protected string $endpoint = 'batch';

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function send(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri($this->endpoint),
            $params
        );
    }
}
