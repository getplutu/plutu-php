<?php

namespace Plutu\Services\Interfaces;

use Plutu\Services\Responses\PlutuTlyncApiResponse;
use Plutu\Services\Responses\Callbacks\PlutuApiTlyncCallback;

interface PlutuTlyncServiceInterface
{
    /**
     * Confirm a payment with the payment gateway.
     *
     * @param float $amount The amount of the transaction.
     * @param string $invoiceNo The invoice number of the transaction.
     * @param string $returnUrl The URL to return url.
     * @param string $callbackUrl The URL to callback url.
     * @param string|null $customerIp The customer's IP address.
     * @param string|null $lang The language to display the payment page in Plutu.
     *
     * @return PlutuTlyncApiResponse.
     */
    public function confirm(string $mobileNumber, float $amount, string $invoiceNo, string $returnUrl, string $callbackUrl, ?string $customerIp = null, ?string $lang = null): PlutuTlyncApiResponse;

    /**
     * Handle the callback request from the payment gateway.
     *
     * @param array<string> $parameters The callback parameters.
     *
     * @return PlutuApiTlyncCallback.
     */
    public function callbackHandler(array $parameters): PlutuApiTlyncCallback;

    /**
     * Handle the return request from the payment gateway.
     *
     * @param array<string> $parameters The callback parameters.
     *
     * @return PlutuApiTlyncCallback.
     */
    public function returnHandler(array $parameters): PlutuApiTlyncCallback;
}
