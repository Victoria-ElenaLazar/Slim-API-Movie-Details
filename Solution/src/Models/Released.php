<?php
declare(strict_types=1);

namespace ApiSlim\Models;


use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Released
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $released)
    {
        Assertion::string($this->released, 'Released should have format of: 19 Jun 2023');
    }

    public function toString(): string
    {
        return $this->released;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

}