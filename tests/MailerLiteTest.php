<?php

namespace MailerLite\Tests;

use MailerLite\Endpoints\BrandStyle;
use MailerLite\Endpoints\Email;
use MailerLite\Endpoints\File;
use MailerLite\MailerLite;

class MailerLiteTest extends TestCase
{
    public function test_basic()
    {
        $this->assertTrue(true);
    }

    public function test_email_endpoints_are_registered()
    {
        $mailerLite = new MailerLite(['api_key' => 'api-key']);

        $this->assertInstanceOf(Email::class, $mailerLite->emails);
        $this->assertInstanceOf(BrandStyle::class, $mailerLite->brandStyles);
        $this->assertInstanceOf(File::class, $mailerLite->files);
    }
}
