<?php

namespace MailerLite\Tests\Endpoints;

use Http\Mock\Client;
use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Automation;
use MailerLite\Tests\Support\AssertsRequests;
use MailerLite\Tests\TestCase;
use Psr\Http\Message\ResponseInterface;

class AutomationTest extends TestCase
{
    use AssertsRequests;

    protected Automation $automations;
    protected ResponseInterface $response;
    protected int $automationId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->automations = new Automation(new HttpLayer(self::OPTIONS, $this->client), self::OPTIONS);
        $this->automationId = '123';

        $this->response = $this->createMock(ResponseInterface::class);
        $this->response->method('getStatusCode')->willReturn(200);
        $this->client->addResponse($this->response);
    }

    public function test_read_all()
    {
        $this->automations->get([]);

        $request = $this->client->getLastRequest();

        self::assertEquals('GET', $request->getMethod());
        self::assertEquals('/api/automations', $request->getUri()->getPath());
    }

    public function test_find()
    {
        $this->automations->find($this->automationId);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}", $request->getUri()->getPath());
    }

    public function test_activity()
    {
        $this->automations->activity($this->automationId, []);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/activity", $request->getUri()->getPath());
    }

    public function test_get_ecommerce_stats()
    {
        $this->automations->getEcommerceStats($this->automationId);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/monthly-totals/ecommerce", $request->getUri()->getPath());
    }

    public function test_get_email_ecommerce_stats()
    {
        $this->automations->getEmailEcommerceStats($this->automationId, '456', ['shop_id' => '1']);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/emails/456/ecommerce-overview-stats", $request->getUri()->getPath());
        self::assertEquals('shop_id=1', $request->getUri()->getQuery());
    }

    public function test_get_ecommerce_orders()
    {
        $this->automations->getEcommerceOrders($this->automationId, ['time_frame' => 'all_time']);

        $request = $this->client->getLastRequest();
        self::assertEquals('GET', $request->getMethod());
        self::assertEquals("/api/automations/{$this->automationId}/order-activity", $request->getUri()->getPath());
        self::assertEquals('time_frame=all_time', $request->getUri()->getQuery());
    }

    /**
     * @dataProvider requests
     * @param array<string,mixed> $query
     * @param array<mixed> $body
     */
    public function test_request(callable $call, string $method, string $path, array $query = [], array $body = [])
    {
        $call($this->automations);

        $this->assertLastRequest($method, $path, $query, $body);
    }

    public function requests(): array
    {
        $base = '/api/automations/1';
        $filter = ['filter' => ['status' => 'active']];
        $stats = ['start_date' => '2026-10-01', 'end_date' => '2026-10-31', 'shop_id' => '5', 'excel' => '1'];
        $subscribers = [
            'status' => 'completed',
            'limit' => '10',
            'page' => '2',
            'sort' => '-date',
            'filter' => ['date_from' => '2026-10-01', 'date_to' => '2026-10-31', 'search' => 'dummy@example.com', 'id' => '9'],
        ];
        $create = ['name' => 'Welcome'];
        $update = ['name' => 'New', 'trigger_data' => ['track_ecommerce' => true], 'triggers' => []];
        $test = ['email' => 'dummy@example.com'];
        $addSubscribers = ['step_id' => '2', 'emails' => ['dummy@example.com'], 'groups' => ['3']];
        $readd = ['readd_subscribers_to' => 'selected_step', 'selected_step_id' => '2', 'filter' => ['status' => 'completed', 'search' => 'dummy']];
        $addToGroup = ['destination_groups' => ['3']] + $filter;
        $createTrigger = ['type' => 'subscriber_joins_group', 'data' => ['group_ids' => ['3']]];
        $updateTrigger = ['data' => ['group_ids' => ['3']]];
        $createStep = ['type' => 'delay', 'parent_id' => '2', 'branch' => 'yes', 'move_to_branch' => 'no', 'data' => ['unit' => 'days', 'value' => 2]];
        $updateStep = ['parent_id' => '3', 'data' => ['unit' => 'days', 'value' => 3], 'reschedule_waiting_subscribers' => true];
        $deleteStep = ['branch_to_delete' => 'yes'];
        $copyStep = ['parent_id' => '3', 'branch' => 'yes', 'move_to_branch' => 'no'];
        $reorder = ['dragged_step_id' => '2', 'new_parent_step_id' => '3', 'branch' => 'yes', 'branch_for_child' => 'no'];
        $winner = ['ab_test_winner' => 'a_path'];
        $content = ['html' => '<p>Hi</p>'];
        $templates = ['filter' => ['type' => 'welcome', 'category' => '3'], 'limit' => '10', 'page' => '2'];

        return [
            'create' => [fn (Automation $a) => $a->create($create), 'POST', '/api/automations', [], $create],
            'update' => [fn (Automation $a) => $a->update('1', $update), 'PUT', $base, [], $update],
            'delete' => [fn (Automation $a) => $a->delete('1'), 'DELETE', $base],
            'copy' => [fn (Automation $a) => $a->copy('1'), 'POST', "$base/copy"],
            'enable' => [fn (Automation $a) => $a->enable('1'), 'POST', "$base/enable"],
            'disable' => [fn (Automation $a) => $a->disable('1'), 'POST', "$base/disable"],
            'test' => [fn (Automation $a) => $a->test('1', $test), 'POST', "$base/test", [], $test],
            'add subscribers' => [fn (Automation $a) => $a->addSubscribers('1', $addSubscribers), 'POST', "$base/add-subscribers", [], $addSubscribers],
            'readd' => [fn (Automation $a) => $a->readd('1', $readd), 'POST', "$base/readd", [], $readd],
            'activity add to group' => [fn (Automation $a) => $a->addActivityToGroup('1', $addToGroup), 'POST', "$base/activity/add-to-group", [], $addToGroup],
            'activity cancel' => [fn (Automation $a) => $a->cancelActivity('1', $filter), 'POST', "$base/activity/cancel-automation", [], $filter],
            'activity skip delay' => [fn (Automation $a) => $a->skipActivityDelay('1', $filter), 'POST', "$base/activity/skip-delay", [], $filter],

            'triggers get' => [fn (Automation $a) => $a->getTriggers('1'), 'GET', "$base/triggers"],
            'triggers create' => [fn (Automation $a) => $a->createTrigger('1', $createTrigger), 'POST', "$base/triggers", [], $createTrigger],
            'triggers find' => [fn (Automation $a) => $a->findTrigger('1', '4'), 'GET', "$base/triggers/4"],
            'triggers update' => [fn (Automation $a) => $a->updateTrigger('1', '4', $updateTrigger), 'PUT', "$base/triggers/4", [], $updateTrigger],
            'triggers delete' => [fn (Automation $a) => $a->deleteTrigger('1', '4'), 'DELETE', "$base/triggers/4"],

            'steps get' => [fn (Automation $a) => $a->getSteps('1'), 'GET', "$base/steps/index"],
            'steps create' => [fn (Automation $a) => $a->createStep('1', $createStep), 'POST', "$base/steps", [], $createStep],
            'steps find' => [fn (Automation $a) => $a->findStep('1', '2'), 'GET', "$base/steps/2"],
            'steps update' => [fn (Automation $a) => $a->updateStep('1', '2', $updateStep), 'PUT', "$base/steps/2", [], $updateStep],
            'steps delete' => [fn (Automation $a) => $a->deleteStep('1', '2', $deleteStep), 'DELETE', "$base/steps/2", $deleteStep],
            'steps copy' => [fn (Automation $a) => $a->copyStep('1', '2', $copyStep), 'POST', "$base/steps/2/copy", [], $copyStep],
            'steps reorder' => [fn (Automation $a) => $a->reorderSteps('1', $reorder), 'POST', "$base/steps/reorder", [], $reorder],
            'steps select ab test winner' => [fn (Automation $a) => $a->selectAbTestWinner('1', '2', $winner), 'PUT', "$base/steps/2/select-ab-test-winner", [], $winner],

            'email content get' => [fn (Automation $a) => $a->getEmailContent('1', '6'), 'GET', "$base/emails/6/content"],
            'email content update' => [fn (Automation $a) => $a->updateEmailContent('1', '6', $content), 'PUT', "$base/emails/6/content", [], $content],
            'email report stats' => [fn (Automation $a) => $a->getEmailReportStats('1', '6', $stats), 'GET', "$base/emails/6/report-stats", $stats],
            'email unsubscribe reasons' => [fn (Automation $a) => $a->getEmailUnsubscribeReasons('1', '6'), 'GET', "$base/emails/6/unsubscribe-reasons"],

            'templates get' => [fn (Automation $a) => $a->getTemplates($templates), 'GET', '/api/automation-templates', $templates],
            'templates select' => [fn (Automation $a) => $a->selectTemplate('7'), 'POST', '/api/automations/select-template/7'],

            'step run counts' => [fn (Automation $a) => $a->getStepRunCounts('1'), 'GET', "$base/steps/run-counts"],
            'step run count' => [fn (Automation $a) => $a->getStepRunCount('1', '2'), 'GET', "$base/steps/run-counts/2"],
            'trigger run counts' => [fn (Automation $a) => $a->getTriggerRunCounts('1'), 'GET', "$base/triggers/run-counts"],
            'trigger run count' => [fn (Automation $a) => $a->getTriggerRunCount('1', '4'), 'GET', "$base/triggers/run-counts/4"],
            'ab steps stats' => [fn (Automation $a) => $a->getAbStepsStats('1'), 'GET', "$base/ab-steps-stats"],
            'ab step stats' => [fn (Automation $a) => $a->getAbStepStats('1', '2'), 'GET', "$base/ab-steps-stats/2"],
            'step subscribers' => [fn (Automation $a) => $a->getStepSubscribers('1', '2', $subscribers), 'GET', "$base/activity/steps/2/subscribers", $subscribers],
            'trigger subscribers' => [fn (Automation $a) => $a->getTriggerSubscribers('1', '4', $subscribers), 'GET', "$base/activity/triggers/4/subscribers", $subscribers],
            'monthly email stats' => [fn (Automation $a) => $a->getEmailStats('1', $stats), 'GET', "$base/monthly-totals/email", $stats],
        ];
    }
}
