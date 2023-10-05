<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Poster
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $poster)
    {
        Assertion::url($this->poster, 'The poster should be the type of URL');
    }

    public function toString(): string
    {
        return $this->poster;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}