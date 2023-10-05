<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Genre
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $genre)
    {
        Assertion::string($this->genre, 'The genre should be the type of string.');
        Assertion::minLength($this->genre, 4, 'The genre should be at least 4 characters.');
    }
    public function toString(): string
    {
        return $this->genre;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}