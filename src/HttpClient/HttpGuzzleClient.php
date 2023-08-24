<?php

namespace Plutu\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpGuzzleClient implements HttpClientInterface
{

    /**
     * @var Client The Http Guzzle Client.
     */
    protected ?Client $client = null;

    /**
     * @var HttpGuzzleExceptionHandler The Http Guzzle Exception Handler.
     */
    protected ?HttpGuzzleExceptionHandler $exceptionHandler = null;

    /**
     * Constructor for HttpGuzzleClient.
     *
     * @param Client|null $client
     * @param array<string>  $config
     * @param HttpGuzzleExceptionHandler|null $exceptionHandler
     */
    public function __construct(?Client $client = null, array $config = [], ?HttpGuzzleExceptionHandler $exceptionHandler = null)
    {
        $this->client = $client ? $client : new Client($config);
        $this->exceptionHandler = $exceptionHandler ? $exceptionHandler : new HttpGuzzleExceptionHandler;
    }

    /**
     * Http Request
     *
     * @param  string $url
     * @param  string $method
     * @param  array<string>  $parameters
     * @param  array<string>  $headers
     * @return object
     */
    public function request(string $url, string $method, array $parameters = [], array $headers = []): object
    {
        try {
            $response = $this->client->request($method, $url, ['timeout' => 600, 'headers' => $headers, 'form_params' => $parameters]);
            $responseBody = $response->getBody();
        } catch (GuzzleException $e) {
            $responseBody = $this->exceptionHandler->handle($e);
        }
        return json_decode($responseBody);
    }
}
