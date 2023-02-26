<?php

namespace Plutu\Services\Traits;

trait PlutuApiServiceTrait
{

    /**
     * Get api headers array
     * 
     * @return array
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