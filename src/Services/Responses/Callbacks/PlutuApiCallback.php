<?php

namespace Plutu\Services\Responses\Callbacks;

class PlutuApiCallback
{
    /**
     * @var array<string> The callback parameters.
     */
    private array $parameters = [];

    /**
     * PlutuApiCallback constructor.
     *
     * @param array<string> $parameters The callback parameters.
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Get a specific callback parameter by key.
     *
     * @param string $key The key of the callback parameter.
     * @return string The value of the specified callback parameter.
     */
    public function getParameter(string $key): string
    {
        return $this->parameters[$key] ?? '';
    }

    /**
     * Get all the callback parameters.
     *
     * @return array<string> An array of all the callback parameters.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
