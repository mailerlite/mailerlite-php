# Very short description of the package

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
https://mailerlite.github.io/reference/subscribers.html

### Create

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    'email' => 'subscriber@example.com',
];

$response = $mailerlite->subscribers->create($data);
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

$response = $mailerlite->subscribers->update($subscriberId, $data);
```

<a name="delete-subscriber"></a>

### Delete

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$subscriberId = '123',

$response = $mailerlite->subscribers->delete($subscriberId);
```

<a name="campaign"></a>

## Campaign

<a name="create-campaign"></a>

More information on request parameters:
https://mailerlite.github.io/reference/campaigns.htm

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

$response = $mailerlite->campaigns->create($data);
```

<a name="read-campaign"></a>

### Read

Single record

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123',

$response = $mailerlite->campaigns->find($campaignId);
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

$campaignId = '123',

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

$response = $mailerlite->campaigns->update($campaignId, $data);
```

<a name="delete-campaign"></a>

### Delete

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123',

$response = $mailerlite->campaigns->delete($campaignId);
```

<a name="schedule-campaign"></a>

### Schedule

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123',

$data = [
    'delivery' => 'instant',
];

$response = $mailerlite->campaigns->schedule($campaignId, $data);
```

<a name="cancel-campaign"></a>

### Cancel

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123',

$response = $mailerlite->campaigns->cancel($campaignId);
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
