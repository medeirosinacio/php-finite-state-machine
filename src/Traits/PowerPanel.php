<?php

namespace Automata\Traits;

use Automata\Interfaces\States\State;
use Automata\Interfaces\States\Stateable;
use Automata\States;
use Automata\Transitions;

/**
 * Trait responsavel por isolar as responsabilidades de funcionamento da staemachine
 *
 * @property ?string $uid
 * @property States $states
 * @property Transitions $transitions
 * @property ?Stateable $stateable
 * @property ?State $initialState
 */
trait PowerPanel
{
    public function inicialize(?Stateable $stateable = null): self
    {
        $this->setStateable($stateable);

        $this->assertMachineStateableContext();

        $this->configureInitialState();

        $this->assertMachineDisabled();

        $this->isRunning = true;

        return $this;
    }

    public function shutdown(): self
    {
        $this->assertMachineEnabled();

        $this->isRunning = false;

        return $this;
    }

    public function initialized(): bool
    {
        return $this->isRunning;
    }
}
