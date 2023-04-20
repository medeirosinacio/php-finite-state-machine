<?php

namespace Automata\Traits;

use Automata\Builders\StateBuilder;
use Tests\Mocks\States\StateMock;

/**
 * Use to create builder from enums or another States class
 *
 * @see StateMock
 *
 * @example
 *          StateMock::Processing->timeout(60)
 *          StateMock::Retry->trigger('retrying')
 */
trait StateBuilderAssessors
{
    abstract public function getName(): string;

    public function timeout(int $timeout): StateBuilder
    {
        return StateBuilder::make($this->getName())->timeout($timeout);
    }

    public function action(\Closure $action): StateBuilder
    {
        return StateBuilder::make($this->getName())->action($action);
    }

    public function trigger(string $trigger): StateBuilder
    {
        return StateBuilder::make($this->getName())->trigger($trigger);
    }
}
