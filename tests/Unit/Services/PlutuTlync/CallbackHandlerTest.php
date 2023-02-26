<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuTlync;

use PlutuTests\Unit\PlutuTest;
use Plutu\Services\PlutuTlync;

use Plutu\Services\Exceptions\{
    InvalidSecretKeyException,
    InvalidCallbackHashException
};

/**
 * Test case for the PlutuTlync::callbackHandler() method.
 *
 * @group PlutuTlync
 */
class CallbackHandlerTest extends PlutuTest
{
    use Traits\ValidFakeDataTrait, Traits\InvalidFakeDataTrait;

    protected $callback;

    /**
     * Set up the test case.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test that the callback throws an InvalidSecretKeyException when an invalid secret key is provided.
     *
     * @return void
     * 
     * @throws InvalidSecretKeyException
     */
    public function testInvalidSecretKeyThrowsException(): void
    {
        $this->expectException(InvalidSecretKeyException::class);
        $this->tryTestCallback('', []);
    }

    /**
     * Test that the callback throws an InvalidCallbackHashException when an invalid secret key is provided.
     *
     * @return void
     * 
     * @throws InvalidCallbackHashException
     */
    public function testInvalidCallbackHashExceptionThrowsException(): void
    {
        $this->expectException(InvalidCallbackHashException::class);
        $this->tryTestCallback(null, ['any', 'array', 'data', 'parameters']);
    }

    /**
     * Test that the callback data has the expected transaction approved.
     * 
     * @return void
     */
    public function testSuccessfulCallbackWithValidisApprovedTransaction(): void
    {
        $secretKey = $this->createValidFakeSecretKey();
        $this->tryTestCallback($secretKey);
        $this->assertTrue($this->callback->isApprovedTransaction());
    }

    /**
     * Builds a request for callback with provided credentials and parameters.
     * 
     * @param string $secretKey  Secret API key for authentication
     * @param array  $parameters Array of data parameters for the request
     * 
     * @return void
     */
    private function tryTestCallback(mixed $secretKey = '', ?array $parameters = null): void
    {
        $secretKey = $secretKey ?? $this->createValidFakeSecretKey();
        $parameters = $parameters ?? $this->getTestParameters('callback_payment_parameters', __DIR__);
        $api = new PlutuTlync($this->httpClientMock);
        $api->setCredentials('api_key', 'access_token', $secretKey);
        $this->callback = $api->callbackHandler($parameters);
    }

}