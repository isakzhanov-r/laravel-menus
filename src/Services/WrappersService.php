<?php


namespace IsakzhanovR\Menus\Services;


use Illuminate\Support\Arr;
use IsakzhanovR\Menus\Contracts\AppendedContract;
use IsakzhanovR\Menus\Contracts\HtmlWrappersContract;
use IsakzhanovR\Menus\Helpers\Tag;

final class WrappersService implements HtmlWrappersContract
{
    protected $wrap = [];

    protected $before = null;

    protected $after = null;

    public function wrap(string $element, array $attributes = []): self
    {
        $this->wrap = [$element, $attributes];

        return $this;
    }

    /**
     * @param string|\IsakzhanovR\Menus\Contracts\AppendedContract $item
     */
    public function beforeHtml($item)
    {
        if ($item instanceof AppendedContract) {
            $this->before = $item->render();
        }

        if (is_string($item)) {
            $this->before = $item;
        }

        return $this;
    }

    public function afterHtml($item)
    {
        if ($item instanceof AppendedContract) {
            $this->after = $item->render();
        }

        if (is_string($item)) {
            $this->after = $item;
        }

        return $this;
    }

    public function renderBefore(): ?string
    {
        return $this->before;
    }

    public function renderAfter(): ?string
    {
        return $this->after;
    }

    public function renderWrap(string $content): ?string
    {
        $attributes = new AttributesService(Arr::last($this->wrap, null, []));

        return Tag::withContent($content, Arr::first($this->wrap), $attributes->toString());
    }

    public function isEmptyWrap(): bool
    {
        return empty($this->wrap);
    }

    public function getWrapTag(string $default = null)
    {
        return Arr::first($this->wrap, null, $default ?? '');
    }
}
