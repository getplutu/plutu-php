<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuMpgs\Traits;

/**
 * Trait with methods to generate invalid fake data for use in tests.
 */
trait InvalidFakeDataTrait
{
    /**
     * Create a Invalid fake return url.
     *
     * @return string
     */
    protected function createInvalidFakeReturnUrl(): string
    {
        return (string) base64_encode(openssl_random_pseudo_bytes(32));
    }

    /**
     * Create a Invalid fake invoice number.
     *
     * @return string
     */
    protected function createInvalidFakeInvoiceNumber(): string
    {
        return (string) '';
    }
}
