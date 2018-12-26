<?php

namespace FromTheMind\MiddlewareAugmenter;

use FromTheMind\MiddlewareAugmenter\MiddlewareExecution;
use FromTheMind\MiddlewareAugmenter\Middlewares;
use FromTheMind\MiddlewareAugmenter\TargetHandlerInterface;
use FromTheMind\MiddlewareAugmenter\TargetInterface;
use Psr\Log\LoggerInterface;

class MiddlewareAugmenter
{
    /** @var LoggerInterface */
    private $logger;

    /** @var Middlewares */
    private $middlewares;

    /** @var TargetHandlerInterface */
    private $targetHandler;

    public function __construct(
        LoggerInterface $logger,
        Middlewares $middlewares,
        TargetHandlerInterface $targetHandler
    ) {
        $this->logger = $loger;
        $this->middlewares = $middlewares;
        $this->targetHandler = $targetHandler;
    }

    public function augment(TargetInterface $target): void
    {
        $execution = new MiddlewareExecution(
            $this->logger,
            $this->middlewares,
            $this->targetHandler
        );

        $execution->execute($target);
    }
}