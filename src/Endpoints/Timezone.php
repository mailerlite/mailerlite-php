<?php

namespace MailerLite\Endpoints;

class Timezone extends AbstractEndpoint
{
    protected string $endpoint = 'timezones';

    /**
     * @return array<string, mixed>
     */
    public function get(): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint)
        );
    }
}
