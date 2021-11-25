<?php

namespace IsakzhanovR\Menus\Support;

use Closure;
use http\Exception\BadMethodCallException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use IsakzhanovR\Menus\Contracts\AppendedContract;
use IsakzhanovR\Menus\Services\HtmlAttributesService;
use IsakzhanovR\Menus\Traits\HasHtmlAttributes;
use ReflectionMethod;

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
 * @see methodIf
 */
class Menu implements AppendedContract
{
    use HasHtmlAttributes;

    /**
     * @var string
     */
    protected $menu;

    /**
     * @var array|\Illuminate\Support\Collection
     */
    protected $items = [];

    /**
     * @var array
     */
    protected HtmlAttributesService $htmlAttributes;

    /**
     * @var array
     */
    private $collection = [];

    public function __construct($name = null)
    {
        $this->menu           = $name;
        $this->items          = new Collection();
        $this->htmlAttributes = new HtmlAttributesService();
    }

    public function make(string $name, Closure $callback = null)
    {
        if (is_null($callback)) {
            return $this->find($name);
        }

        call_user_func($callback, $this->build($name));

        return $this->collection[$name];
    }

    public function generate($name, $items, Closure $callback)
    {

    }

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
        $this->items->push($item);

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

        return $this->add(Item::new($href, $title));
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

        return $this->add(Item::new($href, $title));
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

        return $this->add(Item::new($href, $title));
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

    public function fill()
    {

    }

    public function find(string $name)
    {
        if ($this->exists($name)) {
            return $this->collection[$name];
        }

        return $this->build($name);
    }

    public function render()
    {

    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    public function __call($name, $arguments)
    {
        $method = Str::replace('If', '', $name);

        if (Str::contains($name, 'If') && method_exists($this, $method)) {
            $condition = Arr::first($arguments);

            Arr::forget($arguments, [0]);

            return $this->methodIf($method, $condition, $arguments);
        }

        throw new BadMethodCallException("$name method not exist");
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
     * Add a chunk of menu if a (non-strict) condition is met.
     *
     * @param $method
     * @param $condition
     * @param array $arguments
     *
     * @return mixed|void
     * @throws \ReflectionException
     */
    protected function methodIf($method, $condition, array $arguments)
    {
        if ($this->resolveCondition($condition)) {
            $method = new ReflectionMethod($this, $method);

            return $method->invokeArgs($this, array_values($arguments));
        }
    }

    protected function resolveCondition($conditional)
    {
        return is_callable($conditional) ? $conditional() : $conditional;
    }
}
