<?php

namespace Plutu\Services\Responses\Callbacks;

/**
 * This class represents the callback received from the Plutu API for a tlync transaction.
 */
class PlutuApiTlyncCallback extends PlutuApiCallback
{
    /**
     * Checks if the transaction has been approved.
     *
     * @return bool True if the transaction has been approved, false otherwise.
     */
    public function isApprovedTransaction(): bool
    {
        return $this->getParameter('approved') == 1;
    }
}
