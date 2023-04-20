<?php

namespace Tests\Mocks;

use Medeirosinacio\FiniteStateMachine\Interfaces\State;

enum StateMock: string implements State
{
    case Init = 'initial';
    case Processing = 'processing';
    case Pendent = 'pendent';
    case Finished = 'finished';
}
