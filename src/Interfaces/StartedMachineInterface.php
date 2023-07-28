<?php

namespace Automata\Interfaces;

use Automata\Interfaces\States\State;

interface StartedMachineInterface
{
    public function getCurrentState(): State;

    public function trigger(Event|string  $eventName, ...$params): void;
}