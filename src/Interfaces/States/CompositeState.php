<?php

namespace Automata\Interfaces\States;

use Automata\Interfaces\Stateable\ComplexStateable;
use Automata\Interfaces\Stateable\Stateable;

/**
 * A interface "CompositeState" é uma extensão da interface "State" sendo usada para definir um estado composto,
 * ou seja, um estado formado por um conjunto de outros estados menores.
 */
interface CompositeState extends State
{
    public function getSubMachines();

    public function next();

    public function resolve(Stateable|ComplexStateable|null $stateable): void;

    public function isCompleted(): bool;
}
