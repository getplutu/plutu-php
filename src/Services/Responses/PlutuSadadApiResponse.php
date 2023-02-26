<?php

namespace Plutu\Services\Responses;

class PlutuSadadApiResponse
{

    private PlutuApiResponse $plutuApiResponse;

    /**
     * PlutuSadadApiResponse constructor.
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
     * Returns the process id.
     *
     * @return string.
     */
    public function getProcessId()
    {
        return $this->plutuApiResponse->getResultValue('process_id');
    }

    /**
     * Returns the transaction id.
     *
     * @return string.
     */
    public function getTransactionId()
    {
        return $this->plutuApiResponse->getResultValue('transaction_id');
    }
    
}
