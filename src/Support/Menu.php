<?php

namespace IsakzhanovR\Menus\Support;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use IsakzhanovR\Menus\Contracts\AppendedContract;

class Menu implements AppendedContract
{

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
    private $collection = [];

    public function __construct($name = null)
    {
        $this->menu  = $name;
        $this->items = new Collection();
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

    public function submenu($name, $menu = null)
    {
        $submenu = $this->build($name);
        if (is_callable($menu)) {
            call_user_func($menu, $submenu);
        }

        return $this->add($menu);
    }

    public function add(AppendedContract $item)
    {
        $this->items->push($item);

        return $this;
    }

    public function action($action, string $title, $parameters = [], bool $absolute = true)
    {
        if (is_array($action)) {
            $action = implode('@', $action);
        }
        $href = URL::action($action, $parameters, $absolute);

        return $this->add(Item::new($href, $title));
    }

    public function link($path, string $title, $extra = [], bool $secure = null)
    {
        $href = URL::to($path, $extra, $secure);

        return $this->add(Item::new($href, $title));
    }

    public function route(string $name, string $title, $parameters = [], bool $absolute = true)
    {
        $href = URL::route($name, $parameters, $absolute);

        return $this->add(Item::new($href, $title));
    }

    public function html(string $html)
    {
        return $this->add(Html::new($html));
    }

    public function exists(string $name): bool
    {
        return array_key_exists($name, $this->collection);
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

    protected function build(string $name)
    {
        if (! $this->exists($name)) {
            $this->collection[$name] = new self($name);
        }

        return $this->collection[$name];
    }
}
