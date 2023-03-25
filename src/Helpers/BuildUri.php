<?php

namespace MailerLite\Helpers;

class BuildUri
{
    /**
     * @var array<string, mixed>
     */
    protected array $options;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param array<string, mixed> $params
     */
    public function execute(string $path, array $params = []): string
    {
        $paramsString = http_build_query($params);

        return $this->options['protocol'] .
            '://' .
            $this->options['host'] .
            '/'.
            $this->options['api_path'] .
            '/' .
            $path .
            (
                $paramsString
                    ? '?' . $paramsString
                    : ''
            );
    }
}
