<?php

namespace MailerLite\Tests;

use Http\Mock\Client;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Client $client;

    public const OPTIONS = [
        'host' => 'connect.mailerlite.com',
        'protocol' => 'https',
        'api_path' => 'api',
        'api_key' => 'api-key'
    ];
}
