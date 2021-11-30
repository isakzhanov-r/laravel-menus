<?php

namespace IsakzhanovR\Menus\Traits;

use Illuminate\Support\Arr;

trait HasParentAttributes
{
    public function addParentAttribute(string $name, string $value)
    {
        $this->parentAttributes->addAttribute($name, $value);

        return $this;
    }

    public function addParentAttributes(...$attributes)
    {
        $this->parentAttributes->addAttributes($attributes);

        return $this;
    }

    public function addParentClass(...$classes)
    {
        foreach (Arr::flatten($classes) as $class) {
            $this->addAttribute('class', $class);
        }

        return $this;
    }
}
