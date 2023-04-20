<?php

namespace Automata;

use Automata\Abstracts\Collection;

final class Transitions extends Collection
{
    protected function getEntity(): string
    {
        return Transition::class;
    }
}
