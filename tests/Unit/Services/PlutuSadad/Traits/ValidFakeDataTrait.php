<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuSadad\Traits;

/**
 * Trait with methods to generate valid fake data for use in tests.
 */
trait ValidFakeDataTrait
{
    /**
     * Create a valid fake api key.
     *
     * @return string An api key in the correct format.
     */
    protected function createValidFakeApiKey(): string
    {
        return (string) rand(11111111, 9999999);
    }

    /**
     * Create a valid fake access token.
     *
     * @return string An access token in the correct format.
     */
    protected function createValidFakeAccessToken(): string
    {
        return (string) base64_encode(openssl_random_pseudo_bytes(32));
    }

    /**
     * Create a valid fake mobile number.
     *
     * @return string A mobile number in the correct format.
     */
    protected function createValidFakeMobileNumber(): string
    {
        return '0913632323';
    }

    /**
     * Create a valid fake birth year.
     *
     * @return int A birth year between 1940 and the current year - 18.
     */
    protected function createValidFakeBirthYear(): int
    {
        return (int) rand(1940, (int) date('Y') - 18);
    }

    /**
     * Create a valid fake amount.
     *
     * @return float An amount.
     */
    protected function createValidFakeAmount(): float
    {
        return (float) rand(1, 100);
    }

    /**
     * Create a valid fake process Id.
     *
     * @return string An amount.
     */
    protected function createValidFakeProcessId(): string
    {
        return (string) rand(1111111111, 9999999999);
    }


    /**
     * Create a valid fake OTP.
     *
     * @return string An amount.
     */
    protected function createValidFakeOTP(): string
    {
        return (string) rand(111111, 999999);
    }

    /**
     * Create a valid fake invoice number.
     *
     * @return string An amount.
     */
    protected function createValidFakeInvoiceNumber(): string
    {
        return (string) rand(1111111, 9999999999);
    }
}
