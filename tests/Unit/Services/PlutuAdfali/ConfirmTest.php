<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuAdfali;

use PlutuTests\Unit\PlutuTest;
use Plutu\Services\PlutuAdfali;

use Plutu\Services\Exceptions\{
    InvalidApiKeyException,
    InvalidAccessTokenException,
    InvalidProcessIdException,
    InvalidCodeException,
    InvalidInvoiceNoException,
};

/**
 * Test case for the PlutuAdfali::confirm() method.
 *
 * @group PlutuAdfali
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
     * Test that the response throws an InvalidProcessIdException when an invalid process id is provided.
     *
     * @return void
     * 
     * @throws InvalidProcessIdException
     */
    public function testInvalidProcessIdThrowsException(): void
    {
        $processId = $this->createInvalidFakeProcessId();
        $this->expectException(InvalidProcessIdException::class);
        $this->tryTestWithRequest($processId);
    }

    /**
     * Test that the response throws an InvalidCodeException when an invalid OTP is provided.
     *
     * @return void
     * 
     * @throws InvalidCodeException
     */
    public function testInvalidOtpThrowsException(): void
    {
        $otp = $this->createInvalidFakeOTP();
        $this->expectException(InvalidCodeException::class);
        $this->tryTestWithRequest(null, $otp);
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
        $this->tryTestWithRequest(null, null, null, $invoiceNo);
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
        $this->assertArrayHasKey('message', $keys); 
        $this->assertArrayHasKey('result', $keys);
        $this->assertArrayHasKey('transaction_id', $keys['result']);
        $this->assertArrayHasKey('amount', $keys['result']);
    }

    /**
     * Test that the response data has the expected format for a successful request with a valid transaction ID.
     *
     * @return void
     */
    public function testSuccessfulResponseDataFormatForConfirmProcessWithValidTransactionId(): void
    {
        $this->tryTestWithRequest();
        $this->assertIsNumeric($this->response->getTransactionId());
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
     * Builds a request to confirm a Adfali payment.
     *
     * @param mixed $processId The process id associated with the payment, or null to use a valid fake process id.
     * @param mixed $code The OTP associated with the payment, or null to use a valid fake OTP.
     * @param mixed $amount The payment amount, or null to use a valid fake amount.
     * @param mixed $invoiceNo The payment invoice No, or null to use a valid fake invoice number.
     * @return void
     */
    private function tryTestWithRequest(mixed $processId = null, mixed $code = null, mixed $amount = null, mixed $invoiceNo = null): void
    {
        $processId = $processId ?? $this->createValidFakeProcessId();
        $code = $code ?? $this->createValidFakeOTP();
        $amount = $amount ?? $this->createValidFakeAmount();
        $invoiceNo = $invoiceNo ?? $this->createValidFakeInvoiceNumber();

        $api = new PlutuAdfali($this->httpClientMock);
        $api->setCredentials('api_key', 'access_token');
        $this->response = $api->confirm($processId, $code, $amount, $invoiceNo);
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
        $processId = $this->createValidFakeProcessId();
        $code = $this->createValidFakeOTP();
        $amount = $this->createValidFakeAmount();
        $invoiceNo = $this->createValidFakeInvoiceNumber();

        $api = new PlutuAdfali($this->httpClientMock);
        $api->setCredentials($apiKey, $accessToken);
        $this->response = $api->confirm($processId, $code, $amount, $invoiceNo);

    }

}