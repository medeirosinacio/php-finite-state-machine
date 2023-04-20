<?php

namespace Automata;

use Automata\Interfaces\States\ComplexState;
use Closure;

/**
 * @see \Automata\Interfaces\States\State
 * @see \Automata\Interfaces\States\ComplexState
 *
 * {@inheritDoc}
 */
final class State implements ComplexState
{
    public ?Closure $action = null;

    public ?int $timeout = null;

    public ?string $trigger = null;

    public function __construct(
        public string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAction(): ?Closure
    {
        return $this->action;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function getTrigger(): ?string
    {
        return $this->trigger;
    }
}
