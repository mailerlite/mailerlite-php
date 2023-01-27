<?php

namespace MailerLite;

use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Automation;
use MailerLite\Endpoints\Batch;
use MailerLite\Endpoints\Campaign;
use MailerLite\Endpoints\CampaignLanguage;
use MailerLite\Endpoints\Field;
use MailerLite\Endpoints\Form;
use MailerLite\Endpoints\Group;
use MailerLite\Endpoints\Segment;
use MailerLite\Endpoints\Subscriber;
use MailerLite\Endpoints\Timezone;
use MailerLite\Endpoints\Webhook;
use MailerLite\Exceptions\MailerLiteException;

/**
 * This is the PHP SDK for MailerLite
 */
class MailerLite
{
    protected array $options;
    protected static array $defaultOptions = [
        'host' => 'connect.mailerlite.com',
        'protocol' => 'https',
        'api_path' => 'api',
        'api_key' => '',
        'debug' => false,
    ];

    protected ?HttpLayer $httpLayer;

    public Subscriber $subscribers;
    public Campaign $campaigns;
    public Group  $groups;
    public Segment $segments;
    public Field $fields;
    public Form $forms;
    public Automation $automations;
    public Webhook $webhooks;
    public Timezone $timezones;
    public CampaignLanguage $campaignLanguages;
    public Batch $batches;

    public function __construct(array $options = [], ?HttpLayer $httpLayer = null)
    {
        $this->setOptions($options);
        $this->setHttpLayer($httpLayer);
        $this->setEndpoints();
    }

    protected function setOptions(?array $options): void
    {
        $this->options = self::$defaultOptions;

        foreach ($options as $option => $value) {
            if (array_key_exists($option, $this->options)) {
                $this->options[$option] = $value;
            }
        }

        if (!array_key_exists('api_key', $this->options) || is_null($this->options['api_key'])) {
            throw new MailerLiteException('Please set "api_key" in SDK options.');
        }
    }

    protected function setHttpLayer(?HttpLayer $httpLayer = null): void
    {
        $this->httpLayer = $httpLayer ?: new HttpLayer($this->options);
    }

    protected function setEndpoints(): void
    {
        $this->subscribers = new Subscriber($this->httpLayer, $this->options);
        $this->campaigns = new Campaign($this->httpLayer, $this->options);
        $this->forms = new Form($this->httpLayer, $this->options);
        $this->fields = new Field($this->httpLayer, $this->options);
        $this->segments = new Segment($this->httpLayer, $this->options);
        $this->groups = new Group($this->httpLayer, $this->options);
        $this->automations = new Automation($this->httpLayer, $this->options);
        $this->webhooks = new Webhook($this->httpLayer, $this->options);
        $this->timezones = new Timezone($this->httpLayer, $this->options);
        $this->campaignLanguages = new CampaignLanguage($this->httpLayer, $this->options);
        $this->batches = new Batch($this->httpLayer, $this->options);
    }
}
