<?php

namespace Plutu\HttpClient;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use stdClass;

class HttpGuzzleExceptionHandler
{
    /**
     * Handles a GuzzleException and returns a stdClass with an error object.
     *
     * @param GuzzleException $e The exception to handle
     *
     * @return string The JSON-encoded error response
     */
    public function handle(GuzzleException $e): string
    {
        if ($e instanceof ConnectException) {
            $response = $this->buildError($e->getMessage(), 'CONNECT_ERROR');
        } elseif ($e instanceof RequestException) {
            $response = $this->buildError($e->getMessage(), 'REQUEST_ERROR');
        } else {
            $response = $this->buildError($e->getMessage(), 'BACKEND_ERROR');
        }

        if ($e instanceof RequestException && method_exists($e, 'hasResponse') && $e->hasResponse()) {
            $response = $e->getResponse()->getBody();
        }

        return $response;
    }

    /**
     * Builds an error response with the given message and code.
     *
     * @param string $message The error message
     * @param string $code The error code (default: 'BACKEND_ERROR')
     *
     * @return string The JSON-encoded error response
     */
    protected function buildError(string $message, string $code = 'BACKEND_ERROR'): string
    {
        $response = new stdClass();
        $error = new stdClass();
        $error->status = 400;
        $error->code = $code;
        $error->message = $message;
        $response->error = $error;

        return json_encode($response);
    }
}
