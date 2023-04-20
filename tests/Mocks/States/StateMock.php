<?php

namespace Tests\Mocks\States;

use Automata\Interfaces\States\State;
use Automata\Traits\StateBuilderAssessors;

enum StateMock: string implements State
{
    use StateBuilderAssessors;

    case Init = 'initial';
    case New = 'new';
    case Processing = 'processing';
    case Pendent = 'pendent';
    case Completed = 'Completed';
    case Erro = 'erro';
    case Canceled = 'canceled';
    case Retry = 'retry';

    public function getName(): string
    {
        return $this->value;
    }
}
