<?php

namespace FromTheMind\MiddlewareAugmenter;

use FromTheMind\MiddlewareAugmenter\TargetInterface;

/**
 * Middlewares should implement this interface
 */
interface MiddlewareInterface
{
    /**
     * @param callable(TargetInterface):void $next A callable that executes the
     *                                             next middleware in the list.
     */
    public function augment(TargetInterface $augment, callable $next): void;
}
