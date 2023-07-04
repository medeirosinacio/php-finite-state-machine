<?php

namespace Automata\Interfaces;

use Automata\Interfaces\Stateable\Stateable;
use Automata\StateMachine;

interface StrategyMachine
{
    public function from(Stateable $stateable): StateMachine;
}