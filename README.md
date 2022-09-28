# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mailerlite/mailerlite-php.svg?style=flat-square)](https://packagist.org/packages/mailerlite/mailerlite-php)
[![Total Downloads](https://img.shields.io/packagist/dt/mailerlite/mailerlite-php.svg?style=flat-square)](https://packagist.org/packages/mailerlite/mailerlite-php)
![GitHub Actions](https://github.com/mailerlite/mailerlite-php/actions/workflows/main.yml/badge.svg)

# Table of Contents

* [Installation](#installation)
* [Usage](#usage)
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

If you get an error saying “Could not find resource using any discovery strategy.”
it means that all the discovery strategies have failed. Most likely, your project is missing the message factories and/or a PRS-7 implementation.
To resolve this you may run

```bash
$ composer require php-http/curl-client guzzlehttp/psr7 php-http/message
```

## Setup

```bash
composer require mailerlite/mailerlite-php
```

<a name="usage"></a>

# Usage

<a name="campaign"></a>

## Campaign

<a name="create-campaign"></a>

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

$response = $mailerlite->campaign->create($data);
```

<a name="read-campaign"></a>

### Read

Single record

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123',

$response = $mailerlite->campaign->read($campaignId);
```

All records

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$data = [
    'filter' => ['status' => 'sent'],
];

$response = $mailerLite->campaign->readAll($data);
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

$response = $mailerlite->campaign->update($campaignId, $data);
```

<a name="delete-campaign"></a>

### Delete

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123',

$response = $mailerlite->campaign->delete($campaignId);
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

$response = $mailerlite->campaign->schedule($campaignId, $data);
```

<a name="cancel-campaign"></a>

### Cancel

```php
use MailerLite\MailerLite;

$mailerLite = new MailerLite(['api_key' => 'key']);

$campaignId = '123',

$response = $mailerlite->campaign->cancel($campaignId);
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
