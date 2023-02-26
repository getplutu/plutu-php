<?php

namespace Plutu\Services;

use Plutu\Services\Interfaces\PlutuLocalBankCardsServiceInterface;
use Plutu\Services\Responses\PlutuLocalBankCardsApiResponse;
use Plutu\Services\Responses\Callbacks\PlutuApiLocalBankCardsCallback;

class PlutuLocalBankCards extends PlutuService implements PlutuLocalBankCardsServiceInterface
{
    /**
     * @var string The payment gateway identifier.
     */
    protected string $paymentGateway = 'localbankcards';

    /**
     * Confirm a payment with the payment gateway.
     *
     * @param float $amount The payment amount.
     * @param string $invoiceNo The invoice number.
     * @param string $returnUrl The return callback url.
     * @param string|null $customerIp The customer's IP address.
     * @param string|null $lang The language.
     *
     * @return PlutuLocalBankCardsApiResponse.
     */
    public function confirm(float $amount, string $invoiceNo, string $returnUrl, ?string $customerIp = null, ?string $lang = null): PlutuLocalBankCardsApiResponse
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

        return new PlutuLocalBankCardsApiResponse($response);
    }

    /**
     * Callback method for the payment gateway.
     *
     * @param array $parameters The callback parameters.
     *
     * @return PlutuApiLocalBankCardsCallback
     */
    public function callbackHandler(array $parameters): PlutuApiLocalBankCardsCallback
    {

        $this->validateSecretKey();
        $callbackParameters = ['gateway', 'approved', 'canceled', 'invoice_no', 'amount', 'transaction_id'];
        $data = $this->getCallbackParameters($parameters, $callbackParameters);
        $this->checkValidCallbackHash($parameters, $data);

        return new PlutuApiLocalBankCardsCallback($parameters);
        
    }

}
