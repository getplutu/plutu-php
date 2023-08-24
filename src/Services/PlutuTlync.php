<?php

namespace Plutu\Services;

use Plutu\Services\Interfaces\PlutuTlyncServiceInterface;
use Plutu\Services\Responses\PlutuTlyncApiResponse;
use Plutu\Services\Responses\Callbacks\PlutuApiTlyncCallback;

class PlutuTlync extends PlutuService implements PlutuTlyncServiceInterface
{
    /**
     * @var string The payment gateway identifier.
     */
    protected string $paymentGateway = 'tlync';

    /**
     * Confirm a payment with the payment gateway.
     *
     * @param string $mobileNumber The customer's mobile number.
     * @param float $amount The payment amount.
     * @param string $invoiceNo The invoice number.
     * @param string $returnUrl The return url.
     * @param string $callbackUrl The callback url.
     * @param string|null $customerIp The customer's IP address.
     * @param string|null $lang The language.
     *
     * @return PlutuTlyncApiResponse The response from the payment gateway.
     */
    public function confirm(string $mobileNumber, float $amount, string $invoiceNo, string $returnUrl, string $callbackUrl, ?string $customerIp = null, ?string $lang = null): PlutuTlyncApiResponse
    {

        $this->validateSecretKey();
        $this->validateMobileNumber($mobileNumber);
        $this->validateAmount($amount);
        $this->validateInvoiceNo($invoiceNo);
        $this->validateReturnUrl($returnUrl);
        $this->validateCallbackUrl($callbackUrl);

        $parameters = [
            'mobile_number' => $mobileNumber,
            'amount' => $amount,
            'invoice_no' => $invoiceNo,
            'return_url' => $returnUrl,
            'callback_url' => $callbackUrl,
        ];

        if (!is_null($lang) && $lang !== '') {
            $parameters['lang'] = $lang;
        }

        if (!is_null($customerIp) && $customerIp !== '') {
            $parameters['customer_ip'] = $customerIp;
        }

        $response = $this->call($parameters, 'confirm');

        return new PlutuTlyncApiResponse($response);
    }

    /**
     * Callback method for the payment gateway.
     *
     * @param array<string> $parameters The callback parameters.
     *
     * @return PlutuApiTlyncCallback
     */
    public function callbackHandler(array $parameters): PlutuApiTlyncCallback
    {

        $this->validateSecretKey();
        $callbackParameters = ['gateway', 'approved', 'invoice_no', 'amount', 'transaction_id', 'payment_method'];
        $data = $this->getCallbackParameters($parameters, $callbackParameters);
        $this->checkValidCallbackHash($parameters, $data);

        return new PlutuApiTlyncCallback($parameters);
    }

    /**
     * Callback method for the payment gateway.
     *
     * @param array<string> $parameters The callback parameters.
     *
     * @return PlutuApiTlyncCallback
     */
    public function returnHandler(array $parameters): PlutuApiTlyncCallback
    {

        $this->validateSecretKey();
        $callbackParameters = ['approved', 'invoice_no'];
        $data = $this->getCallbackParameters($parameters, $callbackParameters);
        $this->checkValidCallbackHash($parameters, $data);

        return new PlutuApiTlyncCallback($parameters);
    }
}
