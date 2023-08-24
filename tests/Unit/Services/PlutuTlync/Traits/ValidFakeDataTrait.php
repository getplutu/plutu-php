<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuTlync\Traits;

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
     * Create a valid fake secret key.
     *
     * @return string A secret api key in the correct format.
     */
    protected function createValidFakeSecretKey(): string
    {
        return (string) 'sk_40a07a6c0e63818dc2b292c8e302894e08acedfb';
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
     * Create a valid fake callback url.
     *
     * @return string A callback url in the correct format.
     */
    protected function createValidFakeCallbackUrl(): string
    {
        return 'https://example.local/callback';
    }

    /**
     * Create a valid fake return url.
     *
     * @return string A return url in the correct format.
     */
    protected function createValidFakeReturnUrl(): string
    {
        return 'https://example.local/return';
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
     * Create a valid fake invoice number.
     *
     * @return string An amount.
     */
    protected function createValidFakeInvoiceNumber(): string
    {
        return (string) rand(1111111, 9999999999);
    }
}
