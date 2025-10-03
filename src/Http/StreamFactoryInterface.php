<?php

namespace MailerLite\Http;

use Psr\Http\Message\StreamInterface;

interface StreamFactoryInterface
{
    public function createStream(string $content): StreamInterface;
}
