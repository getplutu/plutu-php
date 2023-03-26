<?php

namespace Plutu\Services\Responses\Callbacks;

/**
 * This class represents the callback received from the Plutu API for a MPGS transaction.
 */
class PlutuApiMpgsCallback extends PlutuApiCallback
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

    /**
     * Checks if the transaction has been cancelled.
     *
     * @return bool True if the transaction has been cancelled, false otherwise.
     */
    public function isCanceledTransaction(): bool
    {
        return $this->getParameter('canceled') == 1;
    }
}