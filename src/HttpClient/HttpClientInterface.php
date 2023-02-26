<?php

namespace Plutu\HttpClient;

interface HttpClientInterface
{

    /**
     * Send request.
     *
     * @param string $url The request URL.
     * @param string $method The request method.
     * @param array $parameters The request parameters.
     * @param array $headers The request headers.
     *
     * @return object.
     */
    public function request(string $url, string $method, array $parameters = [], array $headers = []): object;

}
