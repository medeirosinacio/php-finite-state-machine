<?php

namespace Automata\Exceptions;

final class StateMachineNotHasContextStateableException extends \LogicException
{
    public function __construct(
        string $message = 'State Machine not has context entity stateable.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
