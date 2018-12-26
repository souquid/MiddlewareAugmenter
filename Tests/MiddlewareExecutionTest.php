<?php

namespace FromTheMind\Tests\MiddlewareAugmenter;

use FromTheMind\MiddlewareAugmenter\Middlewares;
use FromTheMind\MiddlewareAugmenter\MiddlewareExecution;
use FromTheMind\MiddlewareAugmenter\MiddlewareInterface;
use FromTheMind\MiddlewareAugmenter\TargetInterface;
use FromTheMind\MiddlewareAugmenter\TargetHandlerInterface;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Log\LoggerInterface;

class MiddlewareAugmenterTest extends MockeryTestCase
{
    public function testNoMiddleware()
    {
        $logger = \Mockery::spy(LoggerInterface::class);
        $target = \Mockery::mock(TargetInterface::class);

        $handler = \Mockery::mock(TargetHandlerInterface::class);
        $handler
            ->shouldReceive('handle')
            ->once()
            ->with($target)
        ;

        $execution = new MiddlewareExecution(
            $logger,
            new Middlewares([]),
            $handler
        );

        $execution->execute($target);
    }


    public function testTargetIsAugmented()
    {
        $logger = \Mockery::spy(LoggerInterface::class);
        $target = \Mockery::mock(TargetInterface::class);
        $target2 = \Mockery::mock(TargetInterface::class);

        $middleware1 = \Mockery::mock(MiddlewareInterface::class);
        $middleware1
            ->shouldReceive('augment')
            ->once()
            ->with($target, \Mockery::any())
            ->andReturnUsing(function ($target, $next) use ($target2) {
                $next($target2);
            })
        ;

        $middleware2 = \Mockery::mock(MiddlewareInterface::class);
        $middleware2
            ->shouldReceive('augment')
            ->once()
            ->with($target2, \Mockery::any())
            ->andReturnUsing(function ($target, $next) {
                $next($target);
            })
        ;

        $handler = \Mockery::mock(TargetHandlerInterface::class);
        $handler
            ->shouldReceive('handle')
            ->once()
            ->with($target2)
        ;

        $execution = new MiddlewareExecution(
            $logger,
            new Middlewares([
                $middleware1,
                $middleware2,
            ]),
            $handler
        );

        $execution->execute($target);
    }
}