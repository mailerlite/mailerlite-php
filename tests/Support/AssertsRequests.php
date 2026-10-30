<?php

namespace MailerLite\Tests\Support;

trait AssertsRequests
{
    /**
     * @param array<string, mixed> $query
     * @param array<mixed>|null $body
     */
    protected function assertLastRequest(string $method, string $path, array $query = [], ?array $body = null): void
    {
        $request = $this->client->getLastRequest();
        parse_str($request->getUri()->getQuery(), $actualQuery);

        self::assertEquals($method, $request->getMethod());
        self::assertEquals($path, $request->getUri()->getPath());
        self::assertEquals($query, $actualQuery);
        if ($body !== null) {
            self::assertSame($body, json_decode((string) $request->getBody(), true));
        }
    }
}
