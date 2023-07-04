<?php

namespace Automata\Exceptions;

class MissingFinalStateException extends \LogicException
{
    public function __construct(
        string      $message = 'State Machine not has final state config.',
        int         $code = 0,
        ?\Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
