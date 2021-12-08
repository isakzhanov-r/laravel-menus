<?php

namespace IsakzhanovR\Menus\Traits;

trait HasHtmlWrappers
{
    public function wrap(string $tag, array $attributes = [])
    {
        $this->htmlWrappers->wrap($tag, $attributes);

        return $this;
    }

    /**
     * @param \IsakzhanovR\Menus\Contracts\AppendedContract|string $item
     */
    public function addBefore($item)
    {
        $this->htmlWrappers->beforeHtml($item);

        return $this;
    }

    /**
     * @param \IsakzhanovR\Menus\Contracts\AppendedContract|string $item
     */
    public function addAfter($item)
    {
        $this->htmlWrappers->afterHtml($item);

        return $this;
    }
}
