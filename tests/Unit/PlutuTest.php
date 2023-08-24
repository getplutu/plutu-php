<?php

declare(strict_types=1);

namespace PlutuTests\Unit;

use PHPUnit\Framework\TestCase;
use Plutu\HttpClient\HttpClientInterface;

/**
 * This is the base test case for the Plutu service unit tests.
 *
 * @group baseTest
 */
class PlutuTest extends TestCase
{
    /**
     * The HTTP client mock instance.
     *
     * @var HttpClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $httpClientMock;

    /**
     * Set up the test case.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
    }

    /**
     * Get the expected response for a given test case.
     *
     * @param string $file The name of the test case file.
     * @param string $path The path of the expected response file.
     *
     * @return object The expected response object.
     */
    protected function getTestResponse(string $file, string $path = __DIR__): object
    {
        $expectedResponseFile = require "{$path}/expected_data/{$file}.php";

        return (object) json_decode(json_encode($expectedResponseFile));
    }

    /**
     * Get the expected parameters for a given test case.
     *
     * @param string $file The name of the test case file.
     * @param string $path The path of the expected response file.
     *
     * @return array<string> The expected parameters array.
     */
    protected function getTestParameters(string $file, string $path = __DIR__): array
    {
        $expectedResponseFile = require "{$path}/expected_data/{$file}.php";

        return (array) $expectedResponseFile;
    }

    /**
     * Create a mock request and set the expected response for a test case.
     *
     * @param string $expectedResponseFile The expected response file name for the mock request.
     * @param string $path The path of the expected response file.
     *
     * @return void
     */
    protected function createMockRequest(string $expectedResponseFile, string $path = __DIR__): void
    {
        $expectedResponse = $this->getTestResponse($expectedResponseFile, $path);
        $this->httpClientMock->method('request')->willReturn($expectedResponse);
    }

}
