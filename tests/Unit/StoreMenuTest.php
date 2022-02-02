<?php

namespace Tests\Unit;

use IsakzhanovR\Menus\Facades\Menu;
use Tests\TestCase;

class StoreMenuTest extends TestCase
{
    public function testStoreMenuBasic()
    {
        $menu = Menu::make('menu')
            ->link('/', 'Main')
            ->link('/about', 'About')
            ->submenu('dropdown', function (\IsakzhanovR\Menus\Support\Menu $menu) {
                $menu->addBefore('Dropdowns')
                    ->link('/dropdown/1', 'Dropdown - 1')
                    ->link('/dropdown/2', 'Dropdown - 2')
                    ->link('/dropdown/3', 'Dropdown - 3');
            })
            ->link('/contacts', 'Contacts');

        dd($menu);
    }
}
