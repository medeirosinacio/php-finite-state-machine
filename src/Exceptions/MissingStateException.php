<?php

namespace Automata\Exceptions;

use Automata\Interfaces\States\State;
use Automata\States;

class MissingStateException extends \LogicException
{
    public function __construct(
        State $state,
        States $states,
        string $message = 'The state "%s" does not exist in StateMachine config in [%s].',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: sprintf(
                $message, $state->getName(),
                implode(', ', array_map(fn (State $state) => $state->getName(), $states->toArray()))),
            code: $code,
            previous: $previous
        );
    }
}