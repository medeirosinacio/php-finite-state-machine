<?php

namespace Automata\Exceptions;

use Automata\Interfaces\Event;
use Automata\Transition;
use Automata\Transitions;

class MissingTransitionTriggeredException extends \LogicException
{
    public function __construct(
        string|Event $eventNameTriggered,
        Transitions $transitions,
        string $message = 'The transition triggered "%s" does not exist in transitions [%s].',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: sprintf(
                $message,
                (string) $eventNameTriggered,
                implode(
                    separator: ', ',
                    array: array_map(
                        callback: fn (Transition $transition) => $transition->event->getName(),
                        array: $transitions->toArray()
                    )
                )
            ),
            code: $code,
            previous: $previous
        );
    }
}

