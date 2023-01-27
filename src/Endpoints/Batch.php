<?php

namespace MailerLite\Endpoints;

class Batch extends AbstractEndpoint
{
    protected string $endpoint = 'batch';

    public function send(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri($this->endpoint),
            $params
        );
    }
}
