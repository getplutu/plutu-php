<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuLocalBankCards\Traits;

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
        return (string) 'sk_ac63978fe4bc6defb55045fe84492021d1e87555';
    }

    /**
     * Create a valid fake return url.
     *
     * @return string A return url in the correct format.
     */
    protected function createValidFakeReturnUrl(): string
    {
        return 'https://example.local/callback';
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
