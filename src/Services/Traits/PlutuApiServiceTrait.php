<?php

namespace Plutu\Services\Traits;

trait PlutuApiServiceTrait
{

    /**
     * Get the headers for the API request.
     *
     * @return array<string, string> The array of headers.
     */
    protected function getApiHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'X-API-KEY' => $this->apiKey,
            'Authorization' => 'Bearer ' . $this->accessToken,
        ];
    }

    /**
     * Get api URL
     *
     * @return string
     */
    protected function getApiUrl(string $action): string
    {
        return $this->baseUrl . '/' . $this->version . '/transaction/' . $this->paymentGateway . '/' . $action;
    }
}
