<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use Assert\Assertion;
use Assert\AssertionFailedException;


readonly class Title
{
    /**
     * @throws AssertionFailedException
     */
    public function __construct(private string $title)
    {
        Assertion::minLength($this->title, 3, 'The title should be at least 3 characters!');
        Assertion::string($this->title, "Title must be a string.");

    }

    public function toString(): string
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

}