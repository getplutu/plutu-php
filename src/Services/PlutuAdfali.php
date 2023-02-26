<?php

namespace Plutu\Services;

use Plutu\Services\Interfaces\PlutuAdfaliServiceInterface;
use Plutu\Services\Responses\PlutuAdfaliApiResponse;

class PlutuAdfali extends PlutuService implements PlutuAdfaliServiceInterface
{
    /**
     * @var string The payment gateway identifier.
     */
    protected string $paymentGateway = 'edfali';

    /**
     * Verify a payment with the payment gateway.
     *
     * @param string $mobileNumber The customer's mobile number.
     * @param float $amount The payment amount.
     *
     * @return PlutuAdfaliApiResponse
     */
    public function verify(string $mobileNumber, float $amount): PlutuAdfaliApiResponse
    {

        $this->validateMobileNumber($mobileNumber);
        $this->validateAmount($amount);

        $parameters = [
            'mobile_number' => $mobileNumber,
            'amount' => $amount,
        ];

        $response = $this->call($parameters, 'verify');

        return new PlutuAdfaliApiResponse($response);

    }

    /**
     * Confirm a payment with the payment gateway.
     *
     * @param string $processId The process ID returned from the payment gateway.
     * @param string $code The confirmation code.
     * @param float $amount The payment amount.
     * @param string $invoiceNo The invoice number.
     * @param string|null $customerIp The customer's IP address.
     *
     * @return PlutuAdfaliApiResponse
     */
    public function confirm(string $processId, string $code, float $amount, string $invoiceNo, ?string $customerIp = null): PlutuAdfaliApiResponse
    {

        $this->validateProcessId($processId);
        $this->validateCode($code);
        $this->validateAmount($amount);
        $this->validateInvoiceNo($invoiceNo);

        $parameters = [
            'process_id' => $processId,
            'code' => $code,
            'amount' => $amount,
            'invoice_no' => $invoiceNo,
        ];

        if (!is_null($customerIp) && $customerIp !== '') {
            $parameters['customer_ip'] = $customerIp;
        }

        $response = $this->call($parameters, 'confirm');
        
        return new PlutuAdfaliApiResponse($response);

    }
}
