<?php

namespace Automata;

use Automata\Interfaces\States\ComplexStateable;
use Automata\Interfaces\States\State;

class Stateable implements Interfaces\States\Stateable, ComplexStateable
{
    private ?State $state = null;

    private ?\DateTimeInterface $entryAt = null;

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }

    public function getDateStateDefined(): \DateTimeInterface
    {
        return $this->entryAt ??= new \DateTime();
    }

    public function updateDateStateDefined(): void
    {
        $this->entryAt = new \DateTime();
    }
}