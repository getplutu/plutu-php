<?php

namespace Plutu\HttpClient;

interface HttpClientInterface
{

    /**
     * Send request.
     *
     * @param string $url The request URL.
     * @param string $method The request method.
     * @param array<string> $parameters The request parameters.
     * @param array<string> $headers The request headers.
     *
     * @return object.
     */
    public function request(string $url, string $method, array $parameters = [], array $headers = []): object;

}
