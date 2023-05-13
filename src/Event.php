<?php

namespace Automata;

use Automata\Interfaces\Event as EventStateMachine;

readonly class Event implements EventStateMachine
{
    public function __construct(
        private string $name
    ) {
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}