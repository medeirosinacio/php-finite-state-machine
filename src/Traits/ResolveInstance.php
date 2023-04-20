<?php

namespace Automata\Traits;

use Automata\Interfaces\Builder;

trait ResolveInstance
{
    protected static function resolveInstance(object|string $entity, string $concrete): mixed
    {
        if (is_string($entity)) {
            $entity = new $concrete($entity);
        }

        if ($entity instanceof Builder) {
            return $entity->build();
        }

        return $entity;
    }
}
