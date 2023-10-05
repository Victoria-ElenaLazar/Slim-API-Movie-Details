<?php
declare(strict_types=1);

namespace ApiSlim\Models;


use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Runtime
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $runtime)
    {
        Assertion::string($this->runtime, 'The runtime should have the following format: 166 min');
    }

    public function toString(): string
    {
        return $this->runtime;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}