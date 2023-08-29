<?php

namespace MailerLite\Endpoints;

class Group extends AbstractEndpoint
{
    protected string $endpoint = 'groups';

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
     * @return array<string, mixed>
     */
    public function find(string $groupId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$groupId}"
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
    public function update(string $groupId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$groupId}",
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $groupId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$groupId}")
        );
    }

    /**
     * @param string $groupId
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getSubscribers(string $groupId, array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint . "/{$groupId}/subscribers", $params)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function assignSubscriber(string $groupId, string $subscriberId): array
    {
        return $this->httpLayer->post(
            $this->buildUri('subscribers') . "/{$subscriberId}/groups/{$groupId}"
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function unAssignSubscriber(string $groupId, string $subscriberId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri('subscribers') . "/{$subscriberId}/groups/{$groupId}"
        );
    }
}
