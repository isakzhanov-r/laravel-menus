<?php

namespace IsakzhanovR\Menus\Services;

use IsakzhanovR\Menus\Contracts\HtmlAttributesContract;

final class AttributesService implements HtmlAttributesContract
{
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->addAttributes($attributes);
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
        if (!$this->exists($name)) {
            $this->attributes[$name] = [$value];

            return $this;
        }
        $this->mergeAttributes($name, $value);

        return $this;
    }

    public function toString(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $attributeStrings = [];

        foreach ($this->attributes as $attribute => $value) {
            if (is_null($value) || $value === '') {
                $attributeStrings[] = $attribute;

                continue;
            }
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $attributeStrings[] = "{$attribute}=\"{$value}\"";
        }

        return implode(' ', $attributeStrings);
    }

    public function __toString()
    {
        return $this->toString();
    }

    protected function isEmpty(): bool
    {
        return empty($this->attributes);
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
