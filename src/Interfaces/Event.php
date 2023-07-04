<?php

namespace Automata\Interfaces;

interface Event extends \Stringable
{
    public function getName(): string;
}