[![Latest Version on Packagist](https://img.shields.io/packagist/v/mailerlite/mailerlite-php.svg)](https://packagist.org/packages/mailerlite/mailerlite-php)
[![Tests](https://github.com/mailerlite/mailerlite-php/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/mailerlite/mailerlite-php/actions/workflows/test.yml)
[![PHPStan](https://github.com/mailerlite/mailerlite-php/actions/workflows/static.yml/badge.svg)](https://github.com/mailerlite/mailerlite-php/actions/workflows/static.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/mailerlite/mailerlite-php.svg)](https://packagist.org/packages/mailerlite/mailerlite-php)

# Table of Contents

* [Installation](#installation)
* [Usage](#usage)
    * [Subscriber API](#subscriber-api)
        * [Create](#create-subscriber)
        * [Read](#read-subscriber)
        * [Update](#update-subscriber)
        * [Delete](#delete-subscriber)
        * [Forget](#forget-subscriber)
    * [Campaign API](#email-api)
        * [Create](#create-campaign)
        * [Read](#read-campaign)
        * [Update](#update-campaign)
        * [Delete](#delete-campaign)
        * [Schedule](#schedule-campaign)
        * [Cancel](#cancel-campaign)
        * [Subscriber activity](#campaign-subscriber-activity)
    * [Email API](#email)
        * [Etags, retries and errors](#email-etags)
        * [Read](#read-email)
        * [Read a block](#read-email-block)
        * [Versions](#email-versions)
        * [Create a block](#create-email-block)
        * [Update a block](#update-email-block)
        * [Move a block](#move-email-block)
        * [Delete a block](#delete-email-block)
        * [Validate a block](#validate-email-block)
        * [Update settings](#update-email-settings)
        * [Validate settings](#validate-email-settings)
        * [Save as template](#create-email-template)
    * [Brand styles API](#brand-styles-read)
    * [Files API](#files-read)
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
* [Optional PSR transport (no BC break)](#optional-psr-transport-no-bc-break) 
* [E-commerce (Products / Orders / Customers)](#e-commerce)    
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
$ composer require php-http/curl-client:^2.0 guzzlehttp/psr7 php-http/message
```

If you get a fatal error saying `Declaration of Http\Client\Curl\Client::sendRequest(...) must be compatible with Psr\Http\Client\ClientInterface::sendRequest(...)`,
an old `php-http/curl-client` 1.x is being loaded. Version 1.x does not implement PSR-18, so upgrade it to `^2.0`
and make sure only one Composer autoloader (`vendor/autoload.php`) is loaded in your project.

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

<a name="forget-subscriber"></a>

### Forget

Removes the subscriber and permanently deletes their personal data within 30 days (GDPR). The subscriber can be identified by ID or email. More information:
https://developers.mailerlite.com/docs/subscribers.html#forget-a-subscriber

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$subscriberId = '123';

$response = $mailerLite->subscribers->forget($subscriberId);
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

To create an email for the simple editor, set `editor` and, optionally, a `template_id` to start from:

```php
use MailerLite\Endpoints\Campaign;
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    'type' => 'regular',
    'name' => 'My new campaign',
    'emails' => [
        [
            'subject' => 'My new email',
            'from_name' => 'me',
            'from' => 'me@example.com',
            'editor' => Campaign::EDITOR_SIMPLE,
            'template_id' => '123',
        ]
    ],
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

<a name="email"></a>
## Email API

<a name="email-etags"></a>

### Etags, retries and errors

Every write must send the `etag` from your last read or write of the email. Reads return it in
`$response['body']['etag']`, and each write returns the next one. For `createBlock`, `updateBlock`,
`moveBlock`, `updateSettings` and `createTemplate`, put `etag` in `$data`. `deleteBlock` and
`restoreVersion` take it as an argument.

If the email changed after you read it, the write throws a `MailerLiteHttpException` with status 409:

```php
use MailerLite\Exceptions\MailerLiteHttpException;
use MailerLite\Exceptions\MailerLiteValidationException;

try {
    $response = $mailerLite->emails->deleteBlock($emailId, $blockId, $etag);
    $etag = $response['body']['etag'];
} catch (MailerLiteHttpException $e) {
    if ($e->getErrorCode() === MailerLiteHttpException::VERSION_CONFLICT) {
        // Your copy is out of date. $e->getEtag() is the current etag,
        // but read the email again before you decide to retry.
    }
} catch (MailerLiteValidationException $e) {
    $errors = $e->getErrors(); // keyed by field, empty when the 422 has only a message
}
```

To retry a write safely after a network error, pass an idempotency key as the last argument. Use
the same key for the retry. If the API returns the stored response of the first attempt,
`$response['is_idempotent_replay']` is `true`.

```php
$response = $mailerLite->emails->createBlock($emailId, $data, 'create-block-7f3a');
```

Email, block and version IDs must contain only the digits 0-9. An empty ID throws
`MailerLiteEmptyIdException`, and any other ID that is not valid throws
`MailerLiteInvalidIdException`. Both are thrown before the request is sent.

<a name="read-email"></a>

### Read

Single record

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$response = $mailerLite->emails->find($emailId);
```

Rendered HTML

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$response = $mailerLite->emails->getHtml($emailId);

$html = $response['body']['data']['html'];
```

<a name="read-email-block"></a>

### Read a block

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';
$blockId = '456';

$response = $mailerLite->emails->findBlock($emailId, $blockId);
```

<a name="email-versions"></a>

### Versions

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$response = $mailerLite->emails->getVersions($emailId);
```

Save the current content as a new version

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$response = $mailerLite->emails->createVersion($emailId);
// 201 when a version is saved, 200 when nothing changed since the last one
```

Restore a version

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';
$versionId = '789';
$etag = '42'; // from your last read or write of this email

$response = $mailerLite->emails->restoreVersion($emailId, $versionId, $etag);
```

<a name="create-email-block"></a>

### Create a block

```php
use MailerLite\Endpoints\Email;
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$data = [
    'etag' => '42',
    'type' => Email::BLOCK_TYPE_DIVIDER,
    'editor_content' => new \stdClass(),
    'placement' => ['position' => Email::POSITION_AFTER, 'block_id' => '57'],
];

$response = $mailerLite->emails->createBlock($emailId, $data);
```

A PHP empty array is sent as `[]`. To send an empty `editor_content` object, `{}`, use `new \stdClass()`.

`placement.position` is `start`, `end`, `before` or `after` (the `Email::POSITION_*` constants).
`before` and `after` need a `block_id`.

<a name="update-email-block"></a>

### Update a block

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';
$blockId = '456';

$data = [
    // fields to change, see the API docs
];

$response = $mailerLite->emails->updateBlock($emailId, $blockId, $data);
```

<a name="move-email-block"></a>

### Move a block

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';
$blockId = '456';

$data = [
    'etag' => '42',
    'placement' => ['position' => 'after', 'block_id' => '57'],
];

$response = $mailerLite->emails->moveBlock($emailId, $blockId, $data);
```

<a name="delete-email-block"></a>

### Delete a block

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';
$blockId = '456';

$etag = '42'; // from your last read or write of this email

$response = $mailerLite->emails->deleteBlock($emailId, $blockId, $etag);

$etag = $response['body']['etag']; // the email's new etag
```

<a name="validate-email-block"></a>

### Validate a block

Checks a block without saving it, so no email ID is needed.

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    // block fields, see the API docs
];

$response = $mailerLite->emails->validateBlock($data);
```

<a name="update-email-settings"></a>

### Update settings

```php
use MailerLite\Endpoints\Email;
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$data = [
    'etag' => '42',
    'brand' => ['mode' => Email::BRAND_MODE_CUSTOM, 'background_color' => '#f4f4f4'],
];

$response = $mailerLite->emails->updateSettings($emailId, $data);
```

Send only the keys you want to change. A value you send is stored on this email, even if it is the
same as the inherited value. To go back to the inherited value:

- for a brand colour, send `''`;
- for a footer text, send an empty editor document, such as `['editor_content' => ['content' => []]]`.

An empty string is not accepted for other colours, sizes, `button.border_radius` or `brand.style`.
Those return 422.

<a name="validate-email-settings"></a>

### Validate settings

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$data = [
    // settings fields, see the API docs
];

$response = $mailerLite->emails->validateSettings($emailId, $data);
```

<a name="create-email-template"></a>

### Save as template

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$emailId = '123';

$data = [
    // template fields, see the API docs
];

$response = $mailerLite->emails->createTemplate($emailId, $data);
```

## Brand styles API

<a name="brand-styles-read"></a>
### Read

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->brandStyles->get();
```

## Files API

<a name="files-read"></a>
### Read

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$response = $mailerLite->files->get();
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

<a name="e-commerce"></a>
## E-commerce (Products / Orders / Customers)

E-commerce endpoints follow:
```php
/api/ecommerce/shops/{shopId}/...
```
`shopId` is provided per call (there is no separate `/shops` API).

**Products**
```php
$res = $mailerLite->ecommerceProducts->create('YOUR_SHOP_ID', [
    'title'    => 'Red T-shirt',
    'price'    => 19.99,
    'currency' => 'USD',
]);

$res = $mailerLite->ecommerceProducts->find('YOUR_SHOP_ID', 'PRODUCT_ID');

$res = $mailerLite->ecommerceProducts->update('YOUR_SHOP_ID', 'PRODUCT_ID', [
    'title' => 'New title',
]);
```
**Orders**
```php
$res = $mailerLite->ecommerceOrders->create('YOUR_SHOP_ID', [
   'order_id' => 'ORD-1001',
]);

$res = $mailerLite->ecommerceOrders->find('YOUR_SHOP_ID', 'ORD-1001');

$res = $mailerLite->ecommerceOrders->update('YOUR_SHOP_ID', 'ORD-1001', [
    'status' => 'paid',
]);
```
**Customers**
```php
$res = $mailerLite->ecommerceCustomers->get('YOUR_SHOP_ID', ['limit' => 50]);
$res = $mailerLite->ecommerceCustomers->create('YOUR_SHOP_ID', ['email' => 'user@example.com']);
$res = $mailerLite->ecommerceCustomers->find('YOUR_SHOP_ID', 'CUSTOMER_ID');
```
## Optional PSR transport (no BC break)
```php
use MailerLite\MailerLite;
use MailerLite\Http\Adapters\Psr17FactoryAggregate;
use Nyholm\Psr7\Factory\Psr17Factory; 
use GuzzleHttp\Client as GuzzleHttpClient; 
$ml = new MailerLite(['api_key' => 'YOUR_API_KEY']);

$psr17 = new Psr17Factory();
$factories = new Psr17FactoryAggregate($psr17, $psr17);

$httpClient = new GuzzleHttpClient();

$ml->enablePsrTransport($httpClient, $factories, 'YOUR_API_KEY', 'https://connect.mailerlite.com');

$ml->subscribers->create(['email' => 'subscriber@example.com']);
```

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
