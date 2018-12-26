<?php

namespace FromTheMind\MiddlewareAugmenter;

use FromTheMind\MiddlewareAugmenter\MiddlewareInterface;

class Middlewares
{
    /** @var MiddlewareInterface[] */
    private $middlewares;

    public function __construct(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $this->checkType($middleware);
        }

        $this->middlewares = $middlewares;
    }

    /** @param mixed $key */
    public function has($key): bool
    {
        return isset($this->middlewares[$key]);
    }

    /**
     * @param mixed $key
     *
     * @throws \Exception
     */
    public function get($key): MiddlewareInterface
    {
        if ($this->has($key) === false) {
            throw new \Exception(
                sprintf(
                    'The key `%s` does not exists in the middlewares list.',
                    $key
                )
            );
        }

        return $this->middlewares[$key];
    }

    /**
     * @throws \Exception
     *
     * No typehint in order to check its type.
     */
    private function checkType($middleware)
    {
        $name = function ($value) {
            if (class_exists($value)) {
                return class_name($value);
            }

            return gettype($value);
        };

        if (!$middleware instanceof MiddlewareInterface) {
            throw new \Exception(
                sprintf(
                    'Middlewares require an array of `%s`, receive a `%s`',
                    MiddlewareInterface::class,
                    $name($middleware)
                )
            );
        }

    }
}