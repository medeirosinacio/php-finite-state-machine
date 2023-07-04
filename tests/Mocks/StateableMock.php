<?php

namespace Automata\Tests\Mocks;

use Automata\Interfaces\Stateable\Stateable;
use Automata\Interfaces\States\State;
use Automata\Tests\Mocks\States\StateMock;

class StateableMock implements Stateable
{
    public function __construct(
        protected State $state = StateMock::New
    ) {
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }
}
