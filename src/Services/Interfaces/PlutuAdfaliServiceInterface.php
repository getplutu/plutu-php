<?php

namespace Plutu\Services\Interfaces;

use Plutu\Services\Responses\PlutuAdfaliApiResponse;

interface PlutuAdfaliServiceInterface
{
    /**
     * Verify a payment with the payment gateway.
     *
     * @param string $mobileNumber The mobile number of the customer.
     * @param float $amount The amount to verify.
     *
     * @return PlutuAdfaliApiResponse.
     */
    public function verify(string $mobileNumber, float $amount): PlutuAdfaliApiResponse;

    /**
     * Confirm a payment with the payment gateway.
     *
     * @param string $processId The process ID of the payment.
     * @param string $code The code of the payment.
     * @param float $amount The amount to confirm.
     * @param string $invoiceNo The invoice number of the payment.
     * @param string|null $customerIp The IP address of the customer (optional).
     *
     * @return PlutuAdfaliApiResponse.
     */
    public function confirm(string $processId, string $code, float $amount, string $invoiceNo, ?string $customerIp = null): PlutuAdfaliApiResponse;
}
