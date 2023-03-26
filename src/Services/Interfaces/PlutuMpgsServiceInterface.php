<?php

namespace Plutu\Services\Interfaces;

use Plutu\Services\Responses\PlutuMpgsApiResponse;
use Plutu\Services\Responses\Callbacks\PlutuApiMpgsCallback;

interface PlutuMpgsServiceInterface
{
    /**
     * Confirm a payment with the payment gateway.
     *
     * @param float $amount The payment amount.
     * @param string $invoiceNo The invoice number.
     * @param string $returnUrl The URL to redirect the customer to after payment.
     * @param string|null $customerIp The customer's IP address.
     * @param string|null $lang The language to display the payment page in Plutu.
     *
     * @return PlutuMpgsApiResponse.
     */
    public function confirm(float $amount, string $invoiceNo, string $returnUrl, ?string $customerIp = null, ?string $lang = null): PlutuMpgsApiResponse;

    /**
     * Handle the callback from the payment gateway.
     *
     * @param array $parameters The request array containing the callback data.
     *
     * @return array The payment confirmation data.
     */
    public function callbackHandler(array $parameters): PlutuApiMpgsCallback;
}
