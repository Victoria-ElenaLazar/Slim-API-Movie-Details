<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Director
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $director)
    {
        Assertion::minLength($this->director, 5, 'The director name should be at least 5 characters.');
    }

    public function toString(): string
    {
        return $this->director;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}