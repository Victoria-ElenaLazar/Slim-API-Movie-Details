<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Type
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $type)
    {
        Assertion::minLength($this->type, 3, 'The type of movie should be at least 3 characters.');
    }

    public function toString(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}