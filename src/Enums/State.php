<?php

namespace Automata\Enums;

enum State implements \Automata\Interfaces\States\State
{
    case Unknown;

    public function getName(): string
    {
        return $this->name;
    }
}
