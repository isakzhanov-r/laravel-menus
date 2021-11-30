<?php

namespace IsakzhanovR\Menus\Services;

use Illuminate\Support\Arr;

final class OptionsService
{
    /**
     * @var array
     */
    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
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
