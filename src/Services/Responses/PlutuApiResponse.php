<?php

namespace Plutu\Services\Responses;

/**
 * PlutuApiResponse is a class that represents the response from the Plutu API.
 */
class PlutuApiResponse
{

    private int $statusCode;
    private object $response;

    /**
     * PlutuApiResponse constructor.
     *
     * @param object $response The Plutu API response object to be wrapped.
     */
    public function __construct(object $response)
    {
        $this->response = $response;
        $this->statusCode = $this->getStatusCodeFromResponse($response);
    }

    /**
     * Get status code from response.
     *
     * @return int
     */
    private function getStatusCodeFromResponse(object $response): int
    {
        if (isset($response->status) && !$response->status) {
            return $response->status;
        }
        if (isset($response->error)) {
            return $response->error->status;
        }
        return $response->status;
    }

    /**
     * Check if the request was successful.
     *
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode === 200;
    }

    /**
     * Gets the HTTP status code of the response.
     *
     * @return int The HTTP status code of the response.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Gets the data from the response.
     *
     * @return object The data from the response.
     */
    public function getBody(): object
    {
        return $this->response;
    }

    /**
     * Gets the resutls.
     *
     * @return object The response result.
     */
    public function getResult(): object
    {
        return $this->response->result ?? [];
    }

    /**
     * Gets the result value.
     *
     * @param string|null $key The key to get the value of. If null, the entire result object is returned.
     *
     * @return mixed|null The value of the key, or null if not present.
     */
    public function getResultValue(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->getResult();
        }

        return $this->response->result->$key ?? null;
    }

    /**
     * Check if the request has an error.
     *
     * @return boolean
     */
    public function hasError(): bool
    {
        return $this->statusCode !== 200;
    }

    /**
     * Get the errors from the response.
     *
     * @return ?array<string>
     */
    public function getErrors(): ?array
    {
        return $this->response->error ?? null;
    }

    /**
     * Get the error message from the response.
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->response->error->message ?? '';
    }

    /**
     * Get the error code from the response.
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->response->error->code ?? '';
    }

    /**
     * Get the error fields from the response.
     *
     * @return object|null
     */
    public function getErrorFields(): ?object
    {
        if (isset($this->response->error->fields)) {
            return (object) $this->response->error->fields;
        }

        return null;
    }
}
