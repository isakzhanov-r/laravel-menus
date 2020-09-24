<?php

namespace IsakzhanovR\Menus\Traits;

use Illuminate\Support\Arr;

trait HasHtmlAttributes
{
    public function addAttribute(string $name, string $value)
    {
        $this->htmlAttributes->addAttribute($name, $value);

        return $this;
    }

    public function addAttributes(...$attributes)
    {
        $this->htmlAttributes->addAttributes($attributes);

        return $this;
    }

    public function addClass(...$classes)
    {
        foreach (Arr::flatten($classes) as $class) {
            $this->addAttribute('class', $class);
        }

        return $this;
    }
}
