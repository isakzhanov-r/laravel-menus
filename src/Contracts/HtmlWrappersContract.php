<?php

namespace IsakzhanovR\Menus\Contracts;

interface HtmlWrappersContract
{
    public function wrap(string $tag, array $attributes = []);

    public function beforeHtml($item);

    public function afterHtml($item);
}
