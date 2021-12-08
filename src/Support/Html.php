<?php

namespace IsakzhanovR\Menus\Support;

use IsakzhanovR\Menus\Contracts\AppendedContract;
use IsakzhanovR\Menus\Services\AttributesService;
use IsakzhanovR\Menus\Traits\ConditionalMethod;
use IsakzhanovR\Menus\Traits\HasParentAttributes;

class Html implements AppendedContract
{
    use ConditionalMethod, HasParentAttributes;

    protected $html;

    protected AttributesService $parentAttributes;

    public function __construct(string $html)
    {
        $this->html             = $html;
        $this->parentAttributes = new AttributesService();
    }

    public static function new(string $html)
    {
        return new static($html);
    }

    public function render(): string
    {
        return $this->html;
    }

    public function store()
    {
        // TODO: Implement store() method.
    }
}
