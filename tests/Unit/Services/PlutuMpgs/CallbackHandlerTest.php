<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuMpgs;

use PlutuTests\Unit\PlutuTest;
use Plutu\Services\PlutuMpgs;

use Plutu\Services\Exceptions\{
    InvalidCallbackHashException,
    InvalidSecretKeyException
};

/**
 * Test case for the PlutuMpgs::callbackHandler() method.
 *
 * @group PlutuMpgs
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
     * Test that the callback data has the expected transaction not canceled.
     *
     * @return void
     */
    public function testSuccessfulCallbackWithValidisNotCanceledTransaction(): void
    {
        $secretKey = $this->createValidFakeSecretKey();
        $this->tryTestCallback($secretKey);
        $this->assertFalse($this->callback->isCanceledTransaction());
    }

    /**
     * Test that the callback data has the expected format for a successful request with a valid transaction ID.
     *
     * @return void
     */
    public function testSuccessfulCallbackWithValidisCanceledTransaction(): void
    {
        $secretKey = $this->createValidFakeSecretKey();
        $this->tryTestCallback($secretKey);
        $this->assertFalse($this->callback->isCanceledTransaction());
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
        $api = new PlutuMpgs($this->httpClientMock);
        $api->setCredentials('api_key', 'access_token', $secretKey);
        $this->callback = $api->callbackHandler($parameters);
    }

}