<?php

namespace Plutu\Services;

use Plutu\Services\Interfaces\PlutuSadadServiceInterface;
use Plutu\Services\Responses\PlutuSadadApiResponse;

class PlutuSadad extends PlutuService implements PlutuSadadServiceInterface
{
    /**
     * @var string The payment gateway identifier.
     */
    protected string $paymentGateway = 'sadadapi';

    /**
     * Verify a payment with the payment gateway.
     *
     * @param string $mobileNumber The customer's mobile number.
     * @param int $birthYear The customer's birth year.
     * @param float $amount The payment amount.
     *
     * @return PlutuSadadApiResponse
     */
    public function verify(string $mobileNumber, int $birthYear, float $amount): PlutuSadadApiResponse
    {

        $this->validateSadadMobileNumber($mobileNumber);
        $this->validateBirthYear($birthYear);
        $this->validateAmount($amount);

        $parameters = [
            'mobile_number' => $mobileNumber,
            'birth_year' => $birthYear,
            'amount' => $amount,
        ];

        $response = $this->call($parameters, 'verify');

        return new PlutuSadadApiResponse($response);

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
     * @return PlutuSadadApiResponse
     */
    public function confirm(string $processId, string $code, float $amount, string $invoiceNo, ?string $customerIp = null): PlutuSadadApiResponse
    {

        $this->validateProcessId($processId);
        $this->validateSadadCode($code);
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

        return new PlutuSadadApiResponse($response);

    }
}
