<?php


namespace IsakzhanovR\Menus\Services;


use IsakzhanovR\Menus\Contracts\HtmlWrappersContract;

final class WrappersService implements HtmlWrappersContract
{
    protected $wrap;

    protected $before;

    protected $after;

    public function wrap(string $element, array $attributes = []): self
    {
        $this->wrap = [$element, $attributes];

        return $this;
    }

    public function beforeHtml()
    {

    }

    public function afterHtml()
    {

    }
}
