<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuSadad;

use PlutuTests\Unit\PlutuTest;
use Plutu\Services\PlutuSadad;

use Plutu\Services\Exceptions\{
    InvalidApiKeyException,
    InvalidAccessTokenException,
    InvalidMobileNumberException,
    InvalidBirthYearException,
};

/**
 * Test case for the PlutuSadad::verify() method.
 *
 * @group PlutuSadad
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
     * Test that the response throws an InvalidBirthYearException when an invalid birth year is provided.
     *
     * @return void
     *
     * @throws InvalidBirthYearException
     */
    public function testInvalidBirthYearThrowsException(): void
    {
        $birthYear = $this->createInvalidFakeBirthYear();
        $this->expectException(InvalidBirthYearException::class);
        $this->tryTestWithRequest(null, $birthYear);
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
        $this->assertArrayHasKey('commission', $keys['result']);
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
     * Builds a request to verify a Sadad payment.
     *
     * @param mixed $mobileNumber The mobile number associated with the payment, or null to use a valid fake number.
     * @param mixed $birthYear The birth year associated with the payment, or null to use a valid fake year.
     * @param mixed $amount The payment amount, or null to use a valid fake amount.
     * @return void
     */
    private function tryTestWithRequest(mixed $mobileNumber = null, mixed $birthYear = null, mixed $amount = null): void
    {
        $mobileNumber = $mobileNumber ?? $this->createValidFakeMobileNumber();
        $birthYear = $birthYear ?? $this->createValidFakeBirthYear();
        $amount = $amount ?? $this->createValidFakeAmount();

        $api = new PlutuSadad($this->httpClientMock);
        $api->setCredentials('api_key', 'access_token');
        $this->response = $api->verify($mobileNumber, $birthYear, $amount);
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
        $birthYear = $this->createValidFakeBirthYear();
        $amount = $this->createValidFakeAmount();

        $api = new PlutuSadad($this->httpClientMock);
        $api->setCredentials($apiKey, $accessToken);
        $this->response = $api->verify($mobileNumber, $birthYear, $amount);
    }
}
