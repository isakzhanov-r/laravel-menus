<?php

namespace IsakzhanovR\Menus\Traits;

trait HasHtmlWrappers
{
    public function wrap(string $element, array $attributes = [])
    {
        $this->wrap($element, $attributes);

        return $this;
    }

    /**
     * @param \IsakzhanovR\Menus\Contracts\AppendedContract|callable $item
     */
    public function addBefore($item)
    {

    }

    /**
     * @param \IsakzhanovR\Menus\Contracts\AppendedContract|callable $item
     */
    public function addAfter($item)
    {

    }
}
