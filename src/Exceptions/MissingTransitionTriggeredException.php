<?php

namespace Automata\Exceptions;

class MissingTransitionTriggeredException extends \LogicException
{
    public function __construct(
        string $message = 'The transition does not exist.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}

