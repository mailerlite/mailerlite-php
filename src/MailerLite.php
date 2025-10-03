<?php

namespace MailerLite;

use MailerLite\Common\HttpLayer;
use MailerLite\Common\HttpLayerPsr;
use MailerLite\Common\HttpLayerPsrBridge;
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
use MailerLite\Endpoints\Product;
use MailerLite\Endpoints\Order;
use MailerLite\Endpoints\Customer;
use MailerLite\Endpoints\Cart;
use MailerLite\Endpoints\CartItem;
use MailerLite\Endpoints\Import;

class MailerLite
{
    /** @var array<string, mixed> */
    protected array $options;

    /** @var array<string, mixed> */
    protected static array $defaultOptions = [
        'host' => 'connect.mailerlite.com',
        'protocol' => 'https',
        'api_path' => 'api',
        'api_key' => '',
        'debug' => false,
    ];

    protected HttpLayer $httpLayer;

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
    public Product $ecommerceProducts;
    public Order $ecommerceOrders;
    public Customer $ecommerceCustomers;
    public Cart $ecommerceCarts;
    public CartItem $ecommerceCartItems;
    public Import $ecommerceImport;

    /**
     * @param array<string,mixed> $options
     */
    public function __construct(array $options = [], ?HttpLayer $httpLayer = null)
    {
        $this->setOptions($options);
        $this->setHttpLayer($httpLayer);
        $this->setEndpoints();
    }

    /**
    * @param array<string,mixed> $options
    */
    protected function setOptions(array $options): void
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

    public function enablePsrTransport(
        \MailerLite\Http\ClientInterface $client,
        \MailerLite\Http\RequestFactoryInterface $requestFactory,
        string $apiKey,
        string $baseUrl = 'https://connect.mailerlite.com'
    ): self {
        $psr = new HttpLayerPsr($client, $requestFactory, $apiKey, $baseUrl);
        $this->httpLayer = new HttpLayerPsrBridge($psr);
        $this->setEndpoints();
        return $this;
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
        $this->ecommerceProducts = new Product($this->httpLayer, $this->options);
        $this->ecommerceOrders = new Order($this->httpLayer, $this->options);
        $this->ecommerceCustomers = new Customer($this->httpLayer, $this->options);
        $this->ecommerceCarts = new Cart($this->httpLayer, $this->options);
        $this->ecommerceCartItems = new CartItem($this->httpLayer, $this->options);
        $this->ecommerceImport = new Import($this->httpLayer, $this->options);
    }
}
