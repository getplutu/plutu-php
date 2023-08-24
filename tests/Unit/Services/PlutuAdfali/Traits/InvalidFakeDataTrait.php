<?php

declare(strict_types=1);

namespace PlutuTests\Unit\Services\PlutuAdfali\Traits;

/**
 * Trait with methods to generate innvalid fake data for use in tests.
 */
trait InvalidFakeDataTrait
{
    /**
     * Create a unvalid fake mobile number.
     *
     * @return string A mobile number in the correct format.
     */
    protected function createInvalidFakeMobileNumber(): string
    {
        return (string) rand(1111111111, 99999999);
    }

    /**
     * Create a unvalid fake birth year.
     *
     * @return int A birth year between 1940 and the current year - 18.
     */
    protected function createInvalidFakeBirthYear(): int
    {
        return (int) rand(1, 111);
    }


    /**
     * Create a unvalid fake process Id.
     *
     * @return string
     */
    protected function createInvalidFakeProcessId(): string
    {
        return (string) '';
    }


    /**
     * Create a unvalid fake OTP.
     *
     * @return string An amount.
     */
    protected function createInvalidFakeOTP(): string
    {
        return (string) '';
    }

    /**
     * Create a unvalid fake invoice number.
     *
     * @return string
     */
    protected function createInvalidFakeInvoiceNumber(): string
    {
        return (string) '';
    }
}
