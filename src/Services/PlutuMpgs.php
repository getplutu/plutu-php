<?php

namespace Plutu\Services;

use Plutu\Services\Interfaces\PlutuMpgsServiceInterface;
use Plutu\Services\Responses\PlutuMpgsApiResponse;
use Plutu\Services\Responses\Callbacks\PlutuApiMpgsCallback;

class PlutuMpgs extends PlutuService implements PlutuMpgsServiceInterface
{
    /**
     * @var string The payment gateway identifier.
     */
    protected string $paymentGateway = 'mpgs';

    /**
     * Confirm a payment with the payment gateway.
     *
     * @param float $amount The payment amount.
     * @param string $invoiceNo The invoice number.
     * @param string $returnUrl The return callback url.
     * @param string|null $customerIp The customer's IP address.
     * @param string|null $lang The language.
     *
     * @return PlutuMpgsApiResponse.
     */
    public function confirm(float $amount, string $invoiceNo, string $returnUrl, ?string $customerIp = null, ?string $lang = null): PlutuMpgsApiResponse
    {

        $this->validateSecretKey();
        $this->validateAmount($amount);
        $this->validateInvoiceNo($invoiceNo);
        $this->validateReturnUrl($returnUrl);

        $parameters = [
            'amount' => $amount,
            'invoice_no' => $invoiceNo,
            'return_url' => $returnUrl,
        ];

        if (!is_null($lang) && $lang !== '') {
            $parameters['lang'] = $lang;
        }

        if (!is_null($customerIp) && $customerIp !== '') {
            $parameters['customer_ip'] = $customerIp;
        }

        $response = $this->call($parameters, 'confirm');

        return new PlutuMpgsApiResponse($response);
    }

    /**
     * Callback method for the payment gateway.
     *
     * @param array<string> $parameters The callback parameters.
     *
     * @return PlutuApiMpgsCallback
     */
    public function callbackHandler(array $parameters): PlutuApiMpgsCallback
    {

        $this->validateSecretKey();
        $callbackParameters = ['gateway', 'approved', 'canceled', 'amount', 'currency', 'invoice_no', 'transaction_id'];
        $data = $this->getCallbackParameters($parameters, $callbackParameters);
        $this->checkValidCallbackHash($parameters, $data);

        return new PlutuApiMpgsCallback($parameters);
    }
}
