<?php

namespace Plutu\Services;

use Plutu\Services\Responses\PlutuApiResponse;
use Plutu\HttpClient\HttpGuzzleClient;
use Plutu\HttpClient\HttpClientInterface;

use Plutu\Services\Traits\PlutuValidationTrait;
use Plutu\Services\Traits\PlutuApiServiceTrait;

/**
 * PlutuService is an abstract class that provides a generic interface for interacting with Plutu payment gateway and services.
 */
abstract class PlutuService
{

    use PlutuValidationTrait, PlutuApiServiceTrait;
    
    /**
     * @var string The base URL for the Plutu API.
     */
    protected string $baseUrl = 'https://api.dev.plutus.ly/api';

    /**
     * @var string The version for the Plutu API.
     */
    protected string $version = 'v1';

    /**
     * @var string The Plutu API key.
     */
    protected string $apiKey = '';

    /**
     * @var string The Plutu API access token.
     */
    protected string $accessToken = '';

    /**
     * @var string The Plutu API secret key.
     */
    protected string $secretKey = '';

    /**
     * @var string The payment gateway identifier.
     */
    protected string $paymentGateway = '';

    /**
     * @var HttpClientInterface http client.
     */
    protected HttpClientInterface $httpClient;

    /**
     * Constructor for PlutuService.
     *
     * @param HttpClientInterface|null $httpClient The HTTP client.
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        $this->setHttpClient($httpClient);
    }

    /**
     * Set API Credentials
     *
     * @param string $apiKey The Plutu API key.
     * @param string $accessToken The Plutu API access token.
     * @param string $secretKey The Plutu API secret key.
     */
    public function setCredentials(?string $apiKey='', ?string $accessToken='', ?string $secretKey = ''): self
    {
        $this->apiKey = $apiKey;
        $this->accessToken = $accessToken;
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * Sets the Plutu API key.
     *
     * @param string $apiKey The Plutu API key.
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Sets the Plutu API access token.
     *
     * @param string $accessToken The Plutu API access token.
     */
    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Sets the Plutu API secret key.
     *
     * @param string $secretKey The Plutu API secret key.
     */
    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * Send a call request to the Plutu API.
     *
     * @param array $parameters The parameters for the API request.
     * @param string $action The action to perform on the API request.
     *
     * @return PlutuApiResponse The response from the Plutu API.
     */
    protected function call(array $parameters, string $action): PlutuApiResponse
    {
        $this->validateApiKey();
        $this->validateAccessToken();

        $url = $this->getApiUrl($action);
        $headers = $this->getApiHeaders();
        $response = $this->httpClient->request($url, 'post', $parameters, $headers);

        return new PlutuApiResponse($response);
    }
    
    /**
     * Set HTTP Client
     * 
     * @param HttpClientInterface|null $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new HttpGuzzleClient();
    }

}
