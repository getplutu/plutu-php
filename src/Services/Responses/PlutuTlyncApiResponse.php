<?php

namespace Plutu\Services\Responses;

/**
 * PlutuTlyncApiResponse represents the response from the Plutu Tlync API.
 */
class PlutuTlyncApiResponse
{
    private PlutuApiResponse $plutuApiResponse;

    /**
     * PlutuTlyncApiResponse constructor.
     *
     * @param PlutuApiResponse $plutuApiResponse The Plutu API response object to be wrapped.
     */
    public function __construct(PlutuApiResponse $plutuApiResponse)
    {
        $this->plutuApiResponse = $plutuApiResponse;
    }

    /**
     * Get the original response object.
     *
     * @return PlutuApiResponse
     */
    public function getOriginalResponse(): PlutuApiResponse
    {
        return $this->plutuApiResponse;
    }

    /**
     * Returns the URL to which the user should be redirected to complete the transaction, if available.
     *
     * @return string|null The URL to which the user should be redirected, or null if not available.
     */
    public function getRedirectUrl(): ?string
    {
        return $this->plutuApiResponse->getResultValue('redirect_url');
    }
    
}
