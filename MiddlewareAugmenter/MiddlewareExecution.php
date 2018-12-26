<?php

namespace FromTheMind\MiddlewareAugmenter;

use FromTheMind\MiddlewareAugmenter\Middlewares;
use FromTheMind\MiddlewareAugmenter\TargetHandlerInterface;
use FromTheMind\MiddlewareAugmenter\TargetInterface;
use Psr\Log\LoggerInterface;

class MiddlewareExecution
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
        $this->logger = $logger;
        $this->middlewares = $middlewares;
        $this->targetHandler = $targetHandler;
    }

    public function execute(TargetInterface $target): void
    {
        $this->nextMiddleware(0)($target);
    }

    private function nextMiddleware(int $index): callable
    {
        // Handle the $target when there are no more middleware
        if ($this->middlewares->has($index) === false) {
            return function (TargetInterface $target): void {
                $this->targetHandler->handle($target);
            };
        }

        $middleware = $this->middlewares->get($index);

        return function ($target) use ($middleware, $index) {
            $this->logEnter($middleware, $target);

            $middleware->augment(
                $target,
                $this->nextMiddleware($index + 1)
            );

            $this->logLeave($middleware, $target);
        };
    }


    /** @param object $handler */
    private function logEnter($middleware, TargetInterface $target): void
    {
        $this->logger->debug(
            sprintf(
                'MiddlewareAugmenter: Entering `%s` for `%s`',
                get_class($middleware),
                get_class($target)
            )
        );
    }

    /** @param object $handler */
    private function logLeave($middleware, TargetInterface $target): void
    {
        $this->logger->debug(
            sprintf(
                'MiddlewareAugmenter: Leaving `%s` for `%s`',
                get_class($middleware),
                get_class($target)
            )
        );
    }
}