<?php

namespace Automata\Exceptions;

class MissingStateException extends \LogicException
{
    public function __construct(
        string $message = 'The state does not exist in StateMachine config.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}