<?php

namespace MailerLite\Endpoints;

class Group extends AbstractEndpoint
{
    protected string $endpoint = 'groups';

    public function create(array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri($this->endpoint),
            $params
        );
    }

    public function find(string $groupId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$groupId}"
        );
    }

    public function get(array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint, $params)
        );
    }

    public function update(string $groupId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri($this->endpoint) . "/{$groupId}",
            $params
        );
    }

    public function delete(string $groupId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri($this->endpoint . "/{$groupId}")
        );
    }

    public function getSubscribers(string $groupId): array
    {
        return $this->httpLayer->get(
            $this->buildUri($this->endpoint) . "/{$groupId}/subscribers"
        );
    }

    public function assignSubscriber(string $groupId, string $subscriberId): array
    {
        return $this->httpLayer->post(
            $this->buildUri('subscribers') . "/{$subscriberId}/groups/{$groupId}"
        );
    }

    public function unAssignSubscriber(string $groupId, string $subscriberId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri('subscribers') . "/{$subscriberId}/groups/{$groupId}"
        );
    }
}
