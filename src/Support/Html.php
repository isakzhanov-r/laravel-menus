<?php

namespace IsakzhanovR\Menus\Support;

use IsakzhanovR\Menus\Contracts\AppendedContract;

class Html implements AppendedContract
{
    protected $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    public static function new(string $html)
    {
        return new static($html);
    }

    public function render()
    {
        // TODO: Implement render() method.
    }
}
