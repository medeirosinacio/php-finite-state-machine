<?php

namespace Automata;

use Automata\Interfaces\States\State;

class Stateable implements Interfaces\States\Stateable
{
    public State $state;

    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }
}