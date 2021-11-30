<?php

namespace IsakzhanovR\Menus\Traits;

use BadMethodCallException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionMethod;

trait  ConditionalMethod
{
    public function __call($name, $arguments)
    {
        $method = Str::replace('If', '', $name);

        if (Str::contains($name, 'If') && method_exists($this, $method)) {
            $condition = Arr::first($arguments);

            Arr::forget($arguments, [0]);

            return $this->methodIf($method, $condition, $arguments);
        }

        throw new BadMethodCallException("$name method not exist");
    }

    /**
     * Add a chunk of menu if a (non-strict) condition is met.
     *
     * @param $method
     * @param $condition
     * @param array $arguments
     *
     * @return mixed|void
     * @throws \ReflectionException
     */
    protected function methodIf($method, $condition, array $arguments)
    {
        if ($this->resolveCondition($condition)) {
            $method = new ReflectionMethod($this, $method);

            return $method->invokeArgs($this, array_values($arguments));
        }
    }

    protected function resolveCondition($conditional)
    {
        return is_callable($conditional) ? $conditional() : $conditional;
    }
}
