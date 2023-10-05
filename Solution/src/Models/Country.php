<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Country
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $country)
    {
        Assertion::minLength($this->country, '3', 'The country should be at least 3 characters');
    }

    public function toString(): string
    {
        return $this->country;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}