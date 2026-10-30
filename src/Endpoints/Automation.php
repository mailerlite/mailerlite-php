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
    public function update(string $automationId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri("{$this->endpoint}/{$automationId}"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $automationId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri("{$this->endpoint}/{$automationId}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function copy(string $automationId): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/copy")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function enable(string $automationId): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/enable")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function disable(string $automationId): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/disable")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function test(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/test"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function addSubscribers(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/add-subscribers"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function readd(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/readd"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function addActivityToGroup(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/activity/add-to-group"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function cancelActivity(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/activity/cancel-automation"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function skipActivityDelay(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/activity/skip-delay"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getTriggers(string $automationId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/triggers")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function createTrigger(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/triggers"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function findTrigger(string $automationId, string $triggerId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/triggers/{$triggerId}")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function updateTrigger(string $automationId, string $triggerId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri("{$this->endpoint}/{$automationId}/triggers/{$triggerId}"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteTrigger(string $automationId, string $triggerId): array
    {
        return $this->httpLayer->delete(
            $this->buildUri("{$this->endpoint}/{$automationId}/triggers/{$triggerId}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getSteps(string $automationId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/index")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function createStep(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function findStep(string $automationId, string $stepId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/{$stepId}")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function updateStep(string $automationId, string $stepId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/{$stepId}"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function deleteStep(string $automationId, string $stepId, array $params = []): array
    {
        return $this->httpLayer->delete(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/{$stepId}", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function copyStep(string $automationId, string $stepId, array $params = []): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/{$stepId}/copy"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function reorderSteps(string $automationId, array $params): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/reorder"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function selectAbTestWinner(string $automationId, string $stepId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/{$stepId}/select-ab-test-winner"),
            $params
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getEmailContent(string $automationId, string $emailId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/emails/{$emailId}/content")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function updateEmailContent(string $automationId, string $emailId, array $params): array
    {
        return $this->httpLayer->put(
            $this->buildUri("{$this->endpoint}/{$automationId}/emails/{$emailId}/content"),
            $params
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getEmailReportStats(string $automationId, string $emailId, array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/emails/{$emailId}/report-stats", $params)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getEmailUnsubscribeReasons(string $automationId, string $emailId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/emails/{$emailId}/unsubscribe-reasons")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getTemplates(array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri('automation-templates', $params)
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function selectTemplate(string $templateId): array
    {
        return $this->httpLayer->post(
            $this->buildUri("{$this->endpoint}/select-template/{$templateId}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getStepRunCounts(string $automationId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/run-counts")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getStepRunCount(string $automationId, string $stepId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/steps/run-counts/{$stepId}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getTriggerRunCounts(string $automationId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/triggers/run-counts")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getTriggerRunCount(string $automationId, string $triggerId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/triggers/run-counts/{$triggerId}")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getAbStepsStats(string $automationId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/ab-steps-stats")
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getAbStepStats(string $automationId, string $stepId): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/ab-steps-stats/{$stepId}")
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getStepSubscribers(string $automationId, string $stepId, array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/activity/steps/{$stepId}/subscribers", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getTriggerSubscribers(string $automationId, string $triggerId, array $params): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/activity/triggers/{$triggerId}/subscribers", $params)
        );
    }

    /**
     * @param array<string, mixed> $params
     *
     * @return array<string, mixed>
     */
    public function getEmailStats(string $automationId, array $params = []): array
    {
        return $this->httpLayer->get(
            $this->buildUri("{$this->endpoint}/{$automationId}/monthly-totals/email", $params)
        );
    }
}
