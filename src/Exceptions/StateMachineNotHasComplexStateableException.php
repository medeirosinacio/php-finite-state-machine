<?php

namespace Automata\Exceptions;

final class StateMachineNotHasComplexStateableException extends \LogicException
{
    public function __construct(
        string $message = 'State Machine not has complex interface.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
