<?php

namespace Automata\Exceptions;

class MissingInitialStateException extends \LogicException
{
    public function __construct(
        string $message = 'State Machine not has initial state config.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
