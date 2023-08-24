<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuAdfali;

use PlutuTests\Unit\PlutuTest;
use Plutu\Services\PlutuAdfali;

use Plutu\Services\Exceptions\{
    InvalidApiKeyException,
    InvalidAccessTokenException,
    InvalidMobileNumberException,
};

/**
 * Test case for the PlutuAdfali::verify() method.
 *
 * @group PlutuAdfali
 */
class VerifyTest extends PlutuTest
{
    use Traits\ValidFakeDataTrait, Traits\InvalidFakeDataTrait;

    /**
     * @var mixed
     */
    protected $response;

    /**
     * Set up the test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->createMockRequest('verify_payment_response', __DIR__);
    }

    /**
     * Test that the response throws an InvalidApiKeyException when an invalid api kye is provided.
     *
     * @return void
     *
     * @throws InvalidApiKeyException
     */
    public function testInvalidApiKeyThrowsException(): void
    {
        $accessToken = $this->createValidFakeAccessToken();
        $this->expectException(InvalidApiKeyException::class);
        $this->tryTestWithCredentials('', $accessToken);
    }

    /**
     * Test that the response throws an InvalidAccessTokenException when an invalid access token is provided.
     *
     * @return void
     *
     * @throws InvalidAccessTokenException
     */
    public function testInvalidAccessTokenThrowsException(): void
    {
        $apiKey = $this->createValidFakeApiKey();
        $this->expectException(InvalidAccessTokenException::class);
        $this->tryTestWithCredentials($apiKey);
    }

    /**
     * Test that the response throws an InvalidMobileNumberException when an invalid mobile number is provided.
     *
     * @return void
     *
     * @throws InvalidMobileNumberException
     */
    public function testInvalidMobileNumberThrowsException(): void
    {
        $mobileNumber = $this->createInvalidFakeMobileNumber();
        $this->expectException(InvalidMobileNumberException::class);
        $this->tryTestWithRequest($mobileNumber);
    }

    /**
     * Test that the response data has the expected format for a successful request with valid credentials and parameters.
     *
     * @return void
     */
    public function testSuccessfulResponseDataFormatForVerifyProcessWithValidCredentialsAndParameters(): void
    {
        $this->tryTestWithRequest();

        $responseBody = $this->response->getOriginalResponse()->getBody();
        $keys = (array) json_decode(json_encode($responseBody), true);

        $this->assertArrayHasKey('status', $keys);
        $this->assertArrayHasKey('message', $keys);
        $this->assertArrayHasKey('result', $keys);
        $this->assertArrayHasKey('process_id', $keys['result']);
        $this->assertArrayHasKey('amount', $keys['result']);
    }

    /**
     * Test that the response data has the expected format for a successful request with a successful status.
     *
     * @return void
     */
    public function testSuccessfulResponseDataFormatForVerifyProcessWithValidSuccessfulStatus(): void
    {
        $this->tryTestWithRequest();
        $this->assertTrue($this->response->getOriginalResponse()->isSuccessful());
    }

    /**
     * Test that the response data has the expected format for a successful request with a valid process ID.
     *
     * @return void
     */
    public function testSuccessfulResponseDataFormatForVerifyProcessWithValidProcessId(): void
    {
        $this->tryTestWithRequest();

        $this->assertIsNumeric($this->response->getProcessId());
    }

    /**
     * Builds a request to verify a Adfali payment.
     *
     * @param mixed $mobileNumber The mobile number associated with the payment, or null to use a valid fake number.
     * @param mixed $amount The payment amount, or null to use a valid fake amount.
     * @return void
     */
    private function tryTestWithRequest(mixed $mobileNumber = null, mixed $amount = null): void
    {
        $mobileNumber = $mobileNumber ?? $this->createValidFakeMobileNumber();
        $amount = $amount ?? $this->createValidFakeAmount();

        $api = new PlutuAdfali($this->httpClientMock);
        $api->setCredentials('api_key', 'access_token');
        $this->response = $api->verify($mobileNumber, $amount);
    }

    /**
     * Builds a request with provided credentials.
     *
     * @param mixed $apiKey      API key for authentication
     * @param mixed $accessToken Access token for authentication
     *
     * @return void
     */
    private function tryTestWithCredentials(mixed $apiKey = '', mixed $accessToken = ''): void
    {
        $mobileNumber = $this->createValidFakeMobileNumber();
        $amount = $this->createValidFakeAmount();

        $api = new PlutuAdfali($this->httpClientMock);
        $api->setCredentials($apiKey, $accessToken);
        $this->response = $api->verify($mobileNumber, $amount);
    }
}
