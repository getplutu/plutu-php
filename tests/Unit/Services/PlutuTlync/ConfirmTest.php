<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuTlync;

use PlutuTests\Unit\PlutuTest;
use Plutu\Services\PlutuTlync;

use Plutu\Services\Exceptions\{
    InvalidApiKeyException,
    InvalidAccessTokenException,
    InvalidSecretKeyException,
    InvalidMobileNumberException,
    InvalidInvoiceNoException,
    InvalidReturnUrlException,
    InvalidCallbackUrlException
};

/**
 * Test case for the PlutuTlync::confirm() method.
 *
 * @group PlutuTlync
 */
class ConfirmTest extends PlutuTest
{
    use Traits\ValidFakeDataTrait, Traits\InvalidFakeDataTrait;

    protected $response;

    /**
     * Set up the test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->createMockRequest('confirm_payment_response', __DIR__);
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
        $secretKey = $this->createValidFakeSecretKey();
        $this->expectException(InvalidApiKeyException::class);
        $this->tryTestWithCredentials('', $accessToken, $secretKey);
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
        $secretKey = $this->createValidFakeSecretKey();
        $this->expectException(InvalidAccessTokenException::class);
        $this->tryTestWithCredentials($apiKey, '', $secretKey);
    }

    /**
     * Test that the response throws an InvalidSecretKeyException when an invalid secret key is provided.
     *
     * @return void
     * 
     * @throws InvalidSecretKeyException
     */
    public function testInvalidSecretKeyThrowsException(): void
    {
        $apiKey = $this->createValidFakeApiKey();
        $accessToken = $this->createValidFakeAccessToken();
        $this->expectException(InvalidSecretKeyException::class);
        $this->tryTestWithCredentials($apiKey, $accessToken);
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
     * Test that the response throws an InvalidInvoiceNoException when an invalid invoice number is provided.
     *
     * @return void
     * 
     * @throws InvalidInvoiceNoException
     */
    public function testInvalidInvoiceNoThrowsException(): void
    {
        $invoiceNo = $this->createInvalidFakeInvoiceNumber();
        $this->expectException(InvalidInvoiceNoException::class);
        $this->tryTestWithRequest(null, null, $invoiceNo);
    }

    /**
     * Test that the response throws an InvalidReturnUrlException when an invalid return url is provided.
     *
     * @return void
     * 
     * @throws InvalidReturnUrlException
     */
    public function testInvalidReturnUrlThrowsException(): void
    {
        $returnUrl = $this->createInvalidFakeReturnUrl();
        $this->expectException(InvalidReturnUrlException::class);
        $this->tryTestWithRequest(null, null, null, $returnUrl);
    }

    /**
     * Test that the response throws an InvalidCallbackUrlException when an invalid return url is provided.
     *
     * @return void
     * 
     * @throws InvalidCallbackUrlException
     */
    public function testInvalidCallbackUrlThrowsException(): void
    {
        $callback = $this->createInvalidFakeCallbackUrl();
        $this->expectException(InvalidCallbackUrlException::class);
        $this->tryTestWithRequest(null, null, null, null, $callback);
    }

    /**
     * Test that the response data has the expected format for a successful request with valid credentials and parameters.
     *
     * @return void
     */
    public function testSuccessfulResponseDataFormatForConfirmProcessWithValidCredentialsAndParameters(): void
    {
        $this->tryTestWithRequest();
        $responseBody = $this->response->getOriginalResponse()->getBody();
        $keys = (array) json_decode(json_encode($responseBody), true);

        $this->assertArrayHasKey('status', $keys);  
        $this->assertArrayHasKey('result', $keys);
        $this->assertArrayHasKey('redirect_url', $keys['result']);
    }

    /**
     * Test that the response data has the expected format for a successful request with a valid redirect URL.
     *
     * @return void
     */
    public function testSuccessfulResponseDataFormatForConfirmProcessWithValidgetRedirectUrl(): void
    {
        $this->tryTestWithRequest();
        $this->assertIsString($this->response->getRedirectUrl());
    }

    /**
     * Test that the response data has the expected format for a successful request with a successful status.
     *
     * @return void
     */
    public function testSuccessfulResponseDataFormatForConfirmProcessWithValidSuccessfulStatus(): void
    {
        $this->tryTestWithRequest();
        $this->assertTrue($this->response->getOriginalResponse()->isSuccessful());
    }

    /**
     * Builds a request to confirm a T-Lync services payment.
     *
     * @param mixed $mobileNumber The mobile number associated with the payment, or null to use a valid fake number.
     * @param mixed $amount The payment amount, or null to use a valid fake amount.
     * @param mixed $invoiceNo The payment invoice No, or null to use a valid fake invoice number.
     * @param mixed $returnUrl The payment return URL No, or null to use a valid fake url.
     * @param mixed $callbackUrl The payment callback URL No, or null to use a valid fake url.
     * @return void
     */
    private function tryTestWithRequest(mixed $mobileNumber = null, mixed $amount = null, mixed $invoiceNo = null, mixed $returnUrl = null, mixed $callbackUrl = null): void
    {
        $mobileNumber = $mobileNumber ?? $this->createValidFakeMobileNumber();
        $amount = $amount ?? $this->createValidFakeAmount();
        $invoiceNo = $invoiceNo ?? $this->createValidFakeInvoiceNumber();
        $returnUrl = $returnUrl ?? $this->createValidFakeReturnUrl();
        $callbackUrl = $callbackUrl ?? $this->createValidFakeCallbackUrl();

        $api = new PlutuTlync($this->httpClientMock);
        $api->setCredentials('api_key', 'access_token', 'secret_key');
        $this->response = $api->confirm($mobileNumber, $amount, $invoiceNo, $returnUrl, $callbackUrl);
    }

    /**
     * Builds a request with provided credentials.
     * 
     * @param mixed $apiKey      API key for authentication
     * @param mixed $accessToken Access token for authentication
     * @param mixed $secretKey   Secret API key for authentication
     * 
     * @return void
     */
    private function tryTestWithCredentials(mixed $apiKey = '', mixed $accessToken = '', mixed $secretKey = ''): void
    {
        $mobileNumber = $this->createValidFakeMobileNumber();
        $amount = $this->createValidFakeAmount();
        $invoiceNo = $this->createValidFakeInvoiceNumber();
        $returnUrl = $this->createValidFakeReturnUrl();
        $callbackUrl = $this->createValidFakeCallbackUrl();

        $api = new PlutuTlync($this->httpClientMock);
        $api->setCredentials($apiKey, $accessToken, $secretKey);
        $this->response = $api->confirm($mobileNumber, $amount, $invoiceNo, $returnUrl, $callbackUrl);
    }

}