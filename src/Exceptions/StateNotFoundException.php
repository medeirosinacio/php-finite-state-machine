<?php

namespace Automata\Exceptions;

final class StateNotFoundException extends \LogicException
{
    public function __construct(
        string $message = 'State not ',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
