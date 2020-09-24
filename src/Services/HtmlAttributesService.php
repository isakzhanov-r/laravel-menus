<?php

namespace IsakzhanovR\Menus\Services;

use IsakzhanovR\Menus\Contracts\HtmlAttributesContract;

final class HtmlAttributesService implements HtmlAttributesContract
{
    protected $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function addAttributes(...$attributes)
    {
        foreach (func_get_arg(0) as $key => $value) {
            if (is_array($value)) {
                $this->addAttributes($value);

                continue;
            }
            if (is_int($key)) {
                $key   = $value;
                $value = '';
            }
            $this->addAttribute($key, $value);
        }

        return $this;
    }

    public function addAttribute(string $name, string $value = '')
    {
        if (! $this->exists($name)) {
            $this->attributes[$name] = [$value];

            return $this;
        }
        $this->mergeAttributes($name, $value);

        return $this;
    }

    protected function exists($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    protected function mergeAttributes(string $name, string $value)
    {
        $this->attributes[$name] = array_merge($this->attributes[$name], [$value]);
    }
}
