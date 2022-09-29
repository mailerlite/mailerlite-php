<?php

namespace MailerLite;

use MailerLite\Common\HttpLayer;
use MailerLite\Endpoints\Campaign;
use MailerLite\Endpoints\Subscriber;
use MailerLite\Exceptions\MailerLiteException;

/**
 * This is the PHP SDK for MailerLite
 */
class MailerLite
{
    protected array $options;
    protected static array $defaultOptions = [
        'host' => 'localhost:8080',
        'protocol' => 'http',
        'api_path' => 'api',
        'api_key' => '',
        'debug' => false,
    ];

    protected ?HttpLayer $httpLayer;

    public Subscriber $subscriber;
    public Campaign $campaign;

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
        $this->subscriber = new Subscriber($this->httpLayer, $this->options);
        $this->campaign = new Campaign($this->httpLayer, $this->options);
    }
}
