[![Latest Version on Packagist](https://img.shields.io/packagist/v/mailerlite/mailerlite-php.svg?style=flat-square)](https://packagist.org/packages/mailerlite/mailerlite-php)
[![Total Downloads](https://img.shields.io/packagist/dt/mailerlite/mailerlite-php.svg?style=flat-square)](https://packagist.org/packages/mailerlite/mailerlite-php)
![GitHub Actions](https://github.com/mailerlite/mailerlite-php/actions/workflows/main.yml/badge.svg)

# Table of Contents

* [Installation](#installation)
* [Usage](#usage)
    * [Subscriber API](#subscriber-api)
        * [Create](#create-subscriber)
        * [Read](#read-subscriber)
        * [Update](#update-subscriber)
        * [Delete](#delete-subscriber)
    * [Campaign API](#email-api)
        * [Create](#create-campaign)
        * [Read](#read-campaign)
        * [Update](#update-campaign)
        * [Delete](#delete-campaign)
        * [Schedule](#schedule-campaign)
        * [Cancel](#cancel-campaign)
        * [Subscriber activity](#campaign-subscriber-activity)
    * [Group API](#group)
        * [Create](#group-create)
        * [Update](#group-update)
        * [Read](#group-read)
        * [Delete](#group-delete)
        * [Get subscribers](#group-list-subscribers)
        * [Assign subscriber](#group-assign-subscriber)
        * [Unassign subscriber](#group-unassign-subscriber)
    * [Segment API](#segment)
        * [Update](#segment-update)
        * [Read](#segment-read)
        * [Delete](#segment-delete)
        * [Get subscribers](#segment-list-subscribers)
    * [Field API](#field)
        * [Create](#field-create)
        * [Update](#field-update)
        * [Read](#field-read)
        * [Delete](#field-delete)   
    * [Form API](#form)
      * [Update](#form-update)
      * [Read](#form-read)
      * [Delete](#form-delete)    
      * [Signups](#form-subscribers)  
    * [Automation API](#automation)
      * [Read](#automation-read)
      * [Activity](#automation-activity)  
    * [Webhook API](#webhook)
      * [Create](#webhook-create)
      * [Update](#webhook-update)
      * [Read](#webhook-read)
      * [Delete](#webhook-delete)    
    * [Campaign language API](#campaign-language-read)
    * [Timezone API](#timezone-read)    
    * [Batch API](#batch-send)           
* [Testing](#testing)
* [License](#license)

<a name="installation"></a>

# Installation

## Requirements

- PHP 7.4
- An API Key from MailerLite
- PSR-7 and PSR-18 based HTTP adapter

## Setup

```bash
composer require mailerlite/mailerlite-php
```

If you get an error saying “Could not find resource using any discovery strategy.”
it means that all the discovery strategies have failed. Most likely, your project is missing the message factories and/or a PRS-7 implementation.
To resolve this you may run

```bash
$ composer require php-http/curl-client guzzlehttp/psr7 php-http/message
```

<a name="usage"></a>

# Usage

## Subscriber

<a name="create-subscriber"></a>

More information on request parameters:
https://developers.mailerlite.com/docs/subscribers.html

### Create

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    'email' => 'subscriber@example.com',
];

$response = $mailerLite->subscribers->create($data);
```

<a name="get-subscriber"></a>

### Read

Single record

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$subscriberId = '123';

$response = $mailerLite->subscribers->find($subscriberId);
```

All records

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->subscribers->get();
```

<a name="update-subscriber"></a>

### Update

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$subscriberId = '123',

$data = [
    'fields' => [
        'name' => 'Example',
    ],
];

$response = $mailerLite->subscribers->update($subscriberId, $data);
```

<a name="delete-subscriber"></a>

### Delete

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$subscriberId = '123';

$response = $mailerLite->subscribers->delete($subscriberId);
```

<a name="campaign"></a>

## Campaign

<a name="create-campaign"></a>

More information on request parameters:
https://developers.mailerlite.com/docs/campaigns.html

### Create

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    'type' => 'regular',
    'name' => 'My new campaign',
    'language_id' => 10,
    'emails' => [
        [
            'subject' => 'My new email',
            'from_name' => 'me',
            'from' => 'me@example.com',
            'content' => 'Hello World!',
        ]
    ],
    'filter' => [],
];

$response = $mailerLite->campaigns->create($data);
```

<a name="read-campaign"></a>

### Read

Single record

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123';

$response = $mailerLite->campaigns->find($campaignId);
```

All records

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    'filter' => ['status' => 'sent'],
];

$response = $mailerLite->campaigns->get($data);
```

<a name="update-campaign"></a>

### Update

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123';

$data = [
    'name' => 'Changed campaign name',
    'emails' => [
        [
            'subject' => 'Changed email subject',
            'from_name' => 'Changed from name',
            'from' => 'changed@example.com',
        ]
    ],
];

$response = $mailerLite->campaigns->update($campaignId, $data);
```

<a name="delete-campaign"></a>

### Delete

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123';

$response = $mailerLite->campaigns->delete($campaignId);
```

<a name="schedule-campaign"></a>

### Schedule

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123';

$data = [
    'delivery' => 'instant',
];

$response = $mailerLite->campaigns->schedule($campaignId, $data);
```

<a name="cancel-campaign"></a>

### Cancel

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123';

$response = $mailerLite->campaigns->cancel($campaignId);
```

<a name="campaign-subscriber-activity"></a>

### Subscriber activity of sent campaign
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123';
$data = [
    'type' => 'opened', 
];

$response = $mailerLite->campaigns->getSubscriberActivity($campaignId, $data);
```

<a name="group"></a>
## Group API
More information on request parameters:
https://developers.mailerlite.com/docs/groups.html

<a name="group-create"></a>
### Create

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    "name" => "New group",
];

$response = $mailerLite->groups->create($data);
```
<a name="group-read"></a>
### Read

Single record

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$groupId = '123';

$response = $mailerLite->groups->find($groupId);
```

All records
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->groups->get();
```

<a name="group-update"></a>

### Update
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$groupId = '123';
$data = [
    "name" => "Updated name",
];

$response = $mailerLite->groups->update($groupId, $data);
```

<a name="group-delete"></a>

### Delete
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$groupId = '123';

$response = $mailerLite->groups->delete($groupId);
```

<a name="group-list-subscribers"></a>
### Get subscribers who belong to a group
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$groupId = '123';

$response = $mailerLite->groups->getSubscribers($groupId);
```

<a name="group-assign-subscriber"></a>
### Assign subscriber to a group
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$groupId = '123';
$subscriberId = '456';

$response = $mailerLite->groups->assignSubscriber($groupId, $subscriberId);
```

<a name="group-unassign-subscriber"></a>
### unassign subscriber from a group
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$groupId = '123';
$subscriberId = '456';

$response = $mailerLite->groups->unAssignSubscriber($groupId, $subscriberId);
```

<a name="segment"></a>
## Segment API
More information on request parameters:
https://developers.mailerlite.com/docs/segments.html

<a name="segment-read"></a>

### Read
All records
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->segments->get();
```

<a name="segment-update"></a>

### Update
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$segmentId = '123';
$data = [
    "name" => "Updated name",
];

$response = $mailerLite->segments->update($segmentId, $data);
```

<a name="segment-delete"></a>
### Delete
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$segmentId = '123';

$response = $mailerLite->segments->delete($segmentId);
```

<a name="segment-subscribers"></a>
### Get subscribers who belong to a segment
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$segmentId = '123';

$response = $mailerLite->segments->getSubscribers($segmentId);
```

<a name="field"></a>
## Field API
More information on request parameters:
https://developers.mailerlite.com/docs/fields.html

<a name="field-create"></a>
### Create

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    "name" => "New field",
    "type" => "text",
];

$response = $mailerLite->fields->create($data);
```

<a name="field-read"></a>
### Read

All records
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->fields->get();
```

<a name="field-update"></a>

### Update
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$fieldId = '123';
$data = [
    "name" => "Updated name",
];

$response = $mailerLite->fields->update($fieldId, $data);
```

<a name="field-delete"></a>

### Delete
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$fieldId = '123';

$response = $mailerLite->fields->delete($fieldId);
```

<a name="form"></a>
## Form API
More information on request parameters:
https://developers.mailerlite.com/docs/forms.html

<a name="form-read"></a>
### Read

All records
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->forms->get('popup', []);
```

Single
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$formId = '123';

$response = $mailerLite->forms->find($formId);
```

<a name="form-update"></a>

### Update
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$formId = '123';
$data = [
    "name" => "Updated name",
];

$response = $mailerLite->forms->update($formId, $data);
```

<a name="form-delete"></a>

### Delete
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$formId = '123';

$response = $mailerLite->forms->delete($formId);
```

<a name="form-subscribers"></a>

### Signed up subscribers
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$formId = '123';

$response = $mailerLite->forms->getSignups($formId);
```

<a name="Automation"></a>
## Automation API
More information on request parameters:
https://developers.mailerlite.com/docs/automations.html

<a name="automation-read"></a>
### Read

All records
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->automations->get([]);
```

Single
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$automationId = '123';

$response = $mailerLite->automations->find($automationId);
```

<a name="automation-activity"></a>
### Activity
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$automationId = '123';
$data = [
    'filter' => [
        'status' => 'active',
    ],
];

$response = $mailerLite->automations->activity($automationId, $data);
```

<a name="webhook"></a>
## Webhook API
More information on request parameters:
https://developers.mailerlite.com/docs/webhooks.html

<a name="webook-create"></a>
### Create

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    "name" => "Name",
    "events" => ["subscriber.created"],
    "url": "https://www.cartwright.info/eligendi-soluta-corporis-in-quod-ullam",
];

$response = $mailerLite->webhooks->create($data);
```

<a name="webook-read"></a>
### Read

All records
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->webhooks->get([]);
```

Single
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$webhookId = '123';

$response = $mailerLite->webhooks->find($webhookId);
```

<a name="webhook-update"></a>

### Update
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$webhookId = '123';
$data = [
    "name" => "Updated name",
];

$response = $mailerLite->webhooks->update($webhookId, $data);
```

<a name="webhook-delete"></a>

### Delete
```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$webhookId = '123';

$response = $mailerLite->webhooks->delete($webhookId);
```

## Campaign language API
More information about request parameters on https://developers.mailerlite.com/docs/campaign-languages.html

<a name="campaign-language-read"></a>
### Read

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->campaignLanguages->get();
```

## Timezone API
More information about request parameters on https://developers.mailerlite.com/docs/timezones.html

<a name="timezone-read"></a>
### Read

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->timezones->get();
```

## Batch API
More information about request parameters on https://developers.mailerlite.com/docs/batching.html

<a name="batch-send"></a>
### Send

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    'requests' => [
        [
            'method' => 'post',
            'path' => 'api/subscribers',
            'body' => [
                'email' => 'new_subscriber@mail.com'
            ]
        ]
    ]
];
$response = $mailerLite->batches->send($data);
```

<a name="testing"></a>
# Testing

``` bash
composer test
```

<a name="support-and-feedback"></a>
# Support and Feedback

In case you find any bugs, submit an issue directly here in GitHub.

You are welcome to create SDK for any other programming language.

If you have any troubles using our API or SDK free to contact our support by email [info@mailerlite.com](mailto:info@mailerlite.com)

<a name="license"></a>
# License

[The MIT License (MIT)](LICENSE.md)
