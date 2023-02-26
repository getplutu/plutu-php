<?php

namespace Plutu\Services\Interfaces;

use Plutu\Services\Responses\PlutuAdfaliApiResponse;

interface PlutuAdfaliServiceInterface
{
    /**
     * Verify a payment with the payment gateway.
     *
     * @param array $parameters The payment parameters to verify.
     *
     * @return PlutuAdfaliApiResponse.
     */
    public function verify(string $mobileNumber, float $amount): PlutuAdfaliApiResponse;

    /**
     * Confirm a payment with the payment gateway.
     *
     * @param array $parameters The payment parameters to confirm.
     *
     * @return PlutuAdfaliApiResponse.
     */
    public function confirm(string $processId, string $code, float $amount, string $invoiceNo, ?string $customerIp = null): PlutuAdfaliApiResponse;
}
