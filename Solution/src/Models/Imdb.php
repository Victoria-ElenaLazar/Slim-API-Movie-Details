<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Imdb
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private float $imdb)
    {
        Assertion::float($this->imdb, 'The imdb should be type of float and represent the imdb rating.');
    }

    public function toString(): float
    {
        return $this->imdb;
    }

    public function __toString(): string
    {
        return (string)$this->toString();
    }
}