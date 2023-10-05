<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Actors
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $actors)
    {
        Assertion::minLength($this->actors, 5, 'The actors name should be at least 5 characters.');
        Assertion::string($this->actors, 'The actor name should be the type of string');
    }

    public function toString(): string
    {
        return $this->actors;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}