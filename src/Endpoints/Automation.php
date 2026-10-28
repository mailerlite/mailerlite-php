<?php

namespace MailerLite\Endpoints;

class Automation extends AbstractEndpoint
{
    protected string $endpoint = 'automations';

    /**
     * @return array<string, mixed>
     */
    public function find(string $automationId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$automationId}"
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function get(array $params): array
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
    public function activity(string $automationId, array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint . "/{$automationId}/activity", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getEcommerceStats(string $automationId, array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/monthly-totals/ecommerce", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getEmailEcommerceStats(string $automationId, string $emailId, array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/emails/{$emailId}/ecommerce-overview-stats", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getEcommerceOrders(string $automationId, array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/order-activity", $params)
        );
    }
}
