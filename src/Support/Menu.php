<?php

namespace IsakzhanovR\Menus\Support;

use Closure;
use Illuminate\Support\Facades\URL;
use IsakzhanovR\Menus\Contracts\AppendedContract;
use IsakzhanovR\Menus\Helpers\Config;
use IsakzhanovR\Menus\Helpers\Tag;
use IsakzhanovR\Menus\Services\AttributesService;
use IsakzhanovR\Menus\Services\WrappersService;
use IsakzhanovR\Menus\Traits\ConditionalMethod;
use IsakzhanovR\Menus\Traits\HasHtmlAttributes;
use IsakzhanovR\Menus\Traits\HasHtmlWrappers;
use IsakzhanovR\Menus\Traits\HasParentAttributes;

/**
 * @method $this addIf(callable | bool $conditional, AppendedContract $item)
 * @method $this makeIf(callable | bool $conditional, string $name, Closure $callback = null)
 * @method $this generateIf(callable | bool $conditional,)
 * @method $this submenuIf(callable | bool $conditional, string $name, $menu = null)
 * @method $this actionIf(callable | bool $conditional, $action, string $title, $parameters = [], bool $absolute = true)
 * @method $this linkIf(callable | bool $conditional, $path, string $title, $extra = [], bool $secure = null)
 * @method $this routeIf(callable | bool $conditional, string $name, string $title, $parameters = [], bool $absolute = true)
 * @method $this htmlIf(callable | bool $conditional, string $html)
 * @method $this findIf(callable | bool $conditional, string $name)
 * @method $this renderIf(callable | bool $conditional)
 * @method $this storeIf(callable | bool $conditional)
 *
 * @see ConditionalMethod.methodIf
 */
class Menu implements AppendedContract
{
    use ConditionalMethod;
    use HasHtmlAttributes;
    use HasParentAttributes;
    use HasHtmlWrappers;

    /**
     * @var string
     */
    protected $menu;

    /**
     * @var array
     */
    protected array $items = [];

    protected AttributesService $htmlAttributes;

    protected AttributesService $parentAttributes;

    protected WrappersService $htmlWrappers;

    protected $options;

    /**
     * @var array
     */
    private $collection = [];

    public function __construct($name = null)
    {
        $this->menu             = $name;
        $this->parentAttributes = new AttributesService();
        $this->htmlAttributes   = new AttributesService();
        $this->htmlWrappers     = new WrappersService();
        $this->options          = Config::instance($name);
        $this->bootOptions();
    }

    /**
     * @param string $name
     * @param \Closure|null $callback
     *
     * @return \IsakzhanovR\Menus\Support\Menu|mixed
     */
    public function make(string $name, Closure $callback = null)
    {
        if (is_null($callback)) {
            return $this->find($name);
        }

        call_user_func($callback, $this->build($name));

        return $this->collection[$name];
    }

    /**
     * @param string $name
     * @param $items
     * @param \Closure|null $callback
     *
     * @return $this|\IsakzhanovR\Menus\Support\Menu
     */
    public function generate(string $name, $items, Closure $callback = null)
    {
        return $this->make($name)->fill($items, $callback);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->collection;
    }

    /**
     * @param $name
     * @param null $menu
     *
     * @return $this|\IsakzhanovR\Menus\Support\Menu.add
     */
    public function submenu(string $name, $menu = null)
    {
        $submenu = $this->build($name);
        if (is_callable($menu)) {
            call_user_func($menu, $submenu);
        }

        return $this->add($submenu);
    }

    /**
     * @param \IsakzhanovR\Menus\Contracts\AppendedContract $item
     *
     * @return $this
     */
    public function add(AppendedContract $item)
    {
        if ($item instanceof Item) {
            $item->menu = $this->menu;
        }
        array_push($this->items, $item);

        return $this;
    }

    /**
     * Helper for append link from Controller@method
     *
     * @param $action
     * @param string $title
     * @param array $parameters
     * @param bool $absolute
     *
     * @return $this|\IsakzhanovR\Menus\Support\Menu.add
     */
    public function action($action, string $title, $parameters = [], bool $absolute = true)
    {
        if (is_array($action)) {
            $action = implode('@', $action);
        }
        $href = URL::action($action, $parameters, $absolute);

        return $this->add(Item::new($href, $title, $this->menu));
    }

    /**
     * Helper for append absolute link
     *
     * @param $path
     * @param string $title
     * @param array $extra
     * @param bool|null $secure
     *
     * @return $this|\IsakzhanovR\Menus\Support\Menu.add
     */
    public function link($path, string $title, $extra = [], bool $secure = null)
    {
        $href = URL::to($path, $extra, $secure);

        return $this->add(Item::new($href, $title, $this->menu));
    }

    /**
     * Helper for append link from route
     *
     * @param string $name
     * @param string $title
     * @param array $parameters
     * @param bool $absolute
     *
     * @return $this|\IsakzhanovR\Menus\Support\Menu.add
     */
    public function route(string $name, string $title, $parameters = [], bool $absolute = true)
    {
        $href = URL::route($name, $parameters, $absolute);

        return $this->add(Item::new($href, $title, $this->menu));
    }

    /**
     * Helper for append Html
     *
     * @param string $html
     *
     * @return $this|\IsakzhanovR\Menus\Support\Menu.add
     * @see \IsakzhanovR\Menus\Support\Html
     *
     */
    public function html(string $html)
    {
        return $this->add(Html::new($html));
    }

    public function find(string $name)
    {
        if ($this->exists($name)) {
            return $this->collection[$name];
        }

        return $this->build($name);
    }

    public function render(): string
    {
        $contents = array_map([$this, 'renderItem'], $this->items);

        $content = Tag::withContent($contents, $this->options->get('wrapper_tag'), $this->htmlAttributes);

        $tag = $this->htmlWrappers->renderBefore() . $content . $this->htmlWrappers->renderAfter();

        return $this->htmlWrappers->renderWrap($tag);
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    public function setWrapperTag($value): self
    {
        $this->options->set('wrapper_tag', $value);

        return $this;
    }

    public function setParentTag($value): self
    {
        $this->options->set('parent_tag', $value);

        return $this;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    protected function fill($items, callable $callback = null)
    {
        $menu = $this;

        foreach ($items as $key => $item) {
            if (is_callable($callback)) {
                $menu = $callback($menu, $item, $key) ?: $menu;
            } else {
                $menu->link($key, $item);
            }
        }

        return $menu;
    }

    protected function exists(string $name): bool
    {
        return array_key_exists($name, $this->collection);
    }

    protected function build(string $name)
    {
        if (!$this->exists($name)) {
            $this->collection[$name] = new self($name);
        }

        return $this->collection[$name];
    }

    /**
     * @param \IsakzhanovR\Menus\Contracts\AppendedContract | \IsakzhanovR\Menus\Support\Item|\IsakzhanovR\Menus\Support\Menu $item
     *
     * @return string
     */
    private function renderItem(AppendedContract $item)
    {
        if (!$item->isEmptyParentAttributes() && !$item instanceof Menu) {
            $this->htmlAttributes->addAttributes($item->getParentAttributes());
        }

        if ($item instanceof Menu) {
            $item->wrap(
                $item->htmlWrappers->getWrapTag(Config::instance($this->menu)->get('parent_tag')),
                array_merge($item->getParentAttributes(), ['class' => Config::instance($this->menu)->get('item_classes')]));
        }

        return $item->render();
    }

    private function bootOptions()
    {
        $this->htmlAttributes->addAttribute('class', $this->options->get('menu_classes'));
    }
}
