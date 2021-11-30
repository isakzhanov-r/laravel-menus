<?php

namespace IsakzhanovR\Menus\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use IsakzhanovR\Menus\Support\Item;
use IsakzhanovR\Menus\Support\Menu as MenuSupport;

/**
 * @method static MenuSupport make(string $name, Closure $callback = null)
 * @method static MenuSupport generate($name, $items, Closure $callback = null)
 * @method static bool exists(string $name)
 * @method static self add(Item $item)
 * @method static self action($action, string $title, $parameters = [], bool $absolute = true)
 * @method static self link($path, string $title, $extra = [], bool $secure = null)
 * @method static self route(string $name, string $title, $parameters = [], bool $absolute = true)
 *
 * @see \IsakzhanovR\Menus\Support\Menu
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MenuSupport::class;
    }
}
