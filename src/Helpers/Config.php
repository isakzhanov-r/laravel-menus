<?php

namespace IsakzhanovR\Menus\Helpers;

use Illuminate\Support\Arr;

class Config
{
    protected static $instances;

    protected array $options;

    private function __construct()
    {
        $this->options = config('laravel_menus');
    }

    public static function instance(string $name = null)
    {
        $subclass = implode('-', [$name, static::class]);

        if (!isset(self::$instances[$subclass])) {

            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }

    public function get(string $name)
    {
        return Arr::get($this->options, $name);
    }

    public function set(string $name, $value)
    {
        Arr::set($this->options, $name, $value);
    }
}
