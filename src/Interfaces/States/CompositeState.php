<?php

namespace Automata\Interfaces\States;

use Automata\CompositeStates;
use Automata\Interfaces\Stateable\CompositeStateable;

/**
 * A interface "CompositeState" é uma extensão da interface "State" sendo usada para definir um estado composto,
 * ou seja, um estado formado por um conjunto de outros estados menores.
 */
interface CompositeState extends State
{
    public function getStates(): CompositeStates;

    public function isAsync(): bool;

    public function triggerOnSuccess(): string;

    public function triggerOnFail(): string;

    public function next();

    public function resolve(CompositeStateable $stateable): void;

    public function isCompleted(): bool;

    public function isFail(): bool;
}
