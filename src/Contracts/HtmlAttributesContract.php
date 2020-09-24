<?php

namespace IsakzhanovR\Menus\Contracts;

interface HtmlAttributesContract
{
    public function addAttributes(...$attributes);

    public function addAttribute(string $name, string $value = '');
}
