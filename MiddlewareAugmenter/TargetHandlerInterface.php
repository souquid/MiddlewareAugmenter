<?php

namespace FromTheMind\MiddlewareAugmenter;

/**
 * This is the handler of TargetInterface.
 */
interface TargetHandlerInterface
{
    public function handle(TargetInterface $target): void;
}
