<?php

namespace IsakzhanovR\Menus\Support;

use Illuminate\Support\Arr;
use IsakzhanovR\Menus\Contracts\AppendedContract;
use IsakzhanovR\Menus\Helpers\Config;
use IsakzhanovR\Menus\Helpers\Tag;
use IsakzhanovR\Menus\Services\AttributesService;
use IsakzhanovR\Menus\Services\WrappersService;
use IsakzhanovR\Menus\Traits\ConditionalMethod;
use IsakzhanovR\Menus\Traits\HasHtmlAttributes;
use IsakzhanovR\Menus\Traits\HasHtmlWrappers;
use IsakzhanovR\Menus\Traits\HasParentAttributes;

class Item implements AppendedContract
{
    use ConditionalMethod;
    use HasHtmlAttributes;
    use HasParentAttributes;
    use HasHtmlWrappers;

    public $menu;

    protected $url = null;

    protected $title;

    protected AttributesService $linkAttributes;

    protected AttributesService $htmlAttributes;

    protected AttributesService $parentAttributes;

    protected WrappersService $htmlWrappers;

    protected function __construct(string $url, string $title)
    {
        $this->url   = $url;
        $this->title = $title;

        $this->parentAttributes = new AttributesService();
        $this->htmlAttributes   = new AttributesService();
        $this->linkAttributes   = new AttributesService();
        $this->htmlWrappers     = new WrappersService();
        $this->bootOptions();
    }

    public static function new(string $url, string $title)
    {
        return new static($url, $title);
    }

    public function render(): string
    {
        $this->linkAttributes->addAttribute('href', $this->url);
        $link = Tag::withContent($this->title, 'a', $this->linkAttributes);

        $tag = $this->htmlWrappers->renderBefore() . $link . $this->htmlWrappers->renderAfter();


        return Tag::withContent($this->htmlWrappers->renderWrap($tag), $this->getOptions()->get('parent_tag'), $this->htmlAttributes);
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    public function addLinkAttribute(string $name, string $value)
    {
        $this->linkAttributes->addAttribute($name, $value);

        return $this;
    }

    public function addLinkAttributes(...$attributes)
    {
        $this->linkAttributes->addAttributes($attributes);

        return $this;
    }

    public function addLinkClass(...$classes)
    {
        foreach (Arr::flatten($classes) as $class) {
            $this->addAttribute('class', $class);
        }

        return $this;
    }

    private function bootOptions()
    {
        $this->htmlAttributes->addAttribute('class', $this->getOptions()->get('item_classes'));
    }

    private function getOptions(): Config
    {
        return Config::instance($this->menu);
    }
}
