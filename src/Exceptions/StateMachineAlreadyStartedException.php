<?php

namespace Automata\Exceptions;

final class StateMachineAlreadyStartedException extends \LogicException
{
    public function __construct(
        string $message = 'The state machine is already started.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
