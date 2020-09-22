<?php

namespace IsakzhanovR\Menus\Support;

use IsakzhanovR\Menus\Contracts\AppendedContract;

class Item implements AppendedContract
{
    protected $url = null;

    protected $title;

    protected function __construct(string $url, string $title)
    {
        $this->url   = $url;
        $this->title = $title;
    }

    public static function new(string $url, string $title)
    {
        return new static($url, $title);
    }

    public function render()
    {

    }
}
