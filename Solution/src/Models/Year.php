<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Year
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $year)
    {
        Assertion::string($this->year, "The year should be formed by 4 characters. Ex: 1964");

    }

    public function toString(): string
    {
        return $this->year;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

}