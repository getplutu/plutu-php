<?php

namespace Plutu\Services\Traits;

use Plutu\Services\Exceptions\InvalidAccessTokenException;
use Plutu\Services\Exceptions\InvalidApiKeyException;
use Plutu\Services\Exceptions\InvalidSecretKeyException;
use Plutu\Services\Exceptions\InvalidMobileNumberException;
use Plutu\Services\Exceptions\InvalidBirthYearException;
use Plutu\Services\Exceptions\InvalidAmountException;
use Plutu\Services\Exceptions\InvalidProcessIdException;
use Plutu\Services\Exceptions\InvalidCodeException;
use Plutu\Services\Exceptions\InvalidInvoiceNoException;
use Plutu\Services\Exceptions\InvalidCallbackHashException;
use Plutu\Services\Exceptions\InvalidReturnUrlException;
use Plutu\Services\Exceptions\InvalidCallbackUrlException;

trait PlutuValidationTrait
{

    /**
     * Validate the given access token.
     *
     * @throws InvalidAccessTokenException If the access token is not configured.
     */
    protected function validateAccessToken(): void
    {
        if (trim($this->accessToken) == '') {
            throw new InvalidAccessTokenException('Access token is not configured');
        }
    }

    /**
     * Validate the given api key.
     *
     * @throws InvalidApiKeyException If the api key is not configured.
     */
    protected function validateApiKey(): void
    {
        if (trim($this->apiKey) == '') {
            throw new InvalidApiKeyException('Api key is not configured');
        }
    }

    /**
     * Validate the given secret key.
     *
     * @throws \InvalidArgumentException If the secret key is configured.
     */
    protected function validateSecretKey(): void
    {
        if (trim($this->secretKey) == '') {
            throw new InvalidSecretKeyException('Secret key is not configured');
        }
    }

    /**
     * Validate the given mobile number.
     *
     * @param string $mobileNumber The mobile number to validate.
     * @throws InvalidMobileNumberException If the mobile number is invalid.
     */
    protected function validateMobileNumber(string $mobileNumber): void
    {
        if (!preg_match('/^09[1-6][0-9]{7}$/i', $mobileNumber)) {
            throw new InvalidMobileNumberException('Invalid mobile number format');
        }
    }

    /**
     * Validate the given Sadad mobile number.
     *
     * @param string $mobileNumber The mobile number to validate.
     * @throws InvalidMobileNumberException If the mobile number is invalid.
     */
    protected function validateSadadMobileNumber(string $mobileNumber): void
    {
        if (!preg_match('/^09[13][0-9]{7}$/i', $mobileNumber)) {
            throw new InvalidMobileNumberException('Invalid mobile number format');
        }
    }

    /**
     * Validate the given birth year.
     *
     * @param int $birthYear The birth year to validate.
     * @throws InvalidBirthYearException If the birth year is invalid.
     */
    protected function validateBirthYear(int $birthYear): void
    {
        $currentYear = date('Y');

        if (!is_numeric($birthYear) || (is_numeric($birthYear) && ($birthYear < 1940 || $birthYear > $currentYear - 12))) {
            throw new InvalidBirthYearException('Invalid birth year');
        }
    }

    /**
     * Validate the given amount.
     *
     * @param float $amount The amount to validate.
     * @throws InvalidAmountException If the amount is invalid.
     */
    protected function validateAmount(float $amount): void
    {
        if (!is_numeric($amount) || $amount <= 0) {
            throw new InvalidAmountException('Invalid amount');
        }
    }

    /**
     * Validate the given process ID.
     *
     * @param string $processId The process ID to validate.
     * @throws InvalidProcessIdException If the process ID is invalid.
     */
    protected function validateProcessId(string $processId): void
    {
        if (!is_numeric($processId)) {
            throw new InvalidProcessIdException('Invalid process ID');
        }
    }

    /**
     * Validate the given confirmation code.
     *
     * @param string $code The confirmation code to validate.
     * @throws InvalidCodeException If the code is invalid.
     */
    protected function validateCode(string $code): void
    {
        if (!is_numeric($code) || strlen($code) !== 4) {
            throw new InvalidCodeException('Invalid code, OTP must be 4 digits');
        }
    }

    /**
     * Validate the given Sadad confirmation code.
     *
     * @param string $code The confirmation code to validate.
     * @throws InvalidCodeException If the code is invalid.
     */
    protected function validateSadadCode(string $code): void
    {
        if (!is_numeric($code) || strlen($code) !== 6) {
            throw new InvalidCodeException('Invalid code, OTP must be 6 digits');
        }
    }

    /**
     * Validate the given invoice number.
     *
     * @param string $invoiceNo The invoice number to validate.
     * @throws InvalidInvoiceNoException If the invoice number is invalid.
     */
    protected function validateInvoiceNo(string $invoiceNo): void
    {
        if (trim($invoiceNo) == '' || !preg_match('/^[A-Za-z0-9\.\-\_]+$/i', $invoiceNo)) {
            throw new InvalidInvoiceNoException('Invalid invoice number');
        }
    }

    /**
     * Validate the given callback url.
     *
     * @param string $url The callback url to validate.
     * @throws InvalidInvoiceNoException If the callback url is invalid.
     */
    protected function validateCallbackUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidCallbackUrlException('Invalid callback url');
        }
    }

    /**
     * Validate the given return url.
     *
     * @param string $url The return url to validate.
     * @throws InvalidInvoiceNoException If the return url is invalid.
     */
    protected function validateReturnUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidReturnUrlException('Invalid return url');
        }
    }

    /**
     * Get the filtered callback parameters from the request.
     *
     * @param array<string> $parameters The request parameters.
     * @param array<string> $callbackParameters The parameters to include.
     * @return string The URL-encoded string parametersf.
     */
    protected function getCallbackParameters(array $parameters, array $callbackParameters = []): string
    {
        return http_build_query(array_filter($parameters, function ($k) use ($callbackParameters) {
            return in_array($k, $callbackParameters);
        }, ARRAY_FILTER_USE_KEY));
    }

    /**
     * Check the validity of the callback hash.
     *
     * @param array<string>  $parameters  The callback parameters.
     * @param string $data        The callback data to hash.
     *
     * @throws InvalidCallbackHashException if the hash verification fails.
     */
    protected function checkValidCallbackHash(array $parameters, string $data): void
    {
        $hash = $parameters['hashed'] ?? false;
        $generatedHash = strtoupper(hash_hmac('sha256', $data, $this->secretKey));
        if (empty($hash) || empty($generatedHash) || !hash_equals($generatedHash, $hash)) {
            throw new InvalidCallbackHashException('Hash verification failed');
        }
    }
}
