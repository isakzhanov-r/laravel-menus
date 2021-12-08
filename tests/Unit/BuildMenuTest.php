<?php

namespace Tests\Unit;

use IsakzhanovR\Menus\Facades\Menu;
use IsakzhanovR\Menus\Support\Item;
use IsakzhanovR\Menus\Support\Menu as MenuSupport;
use Tests\TestCase;

class BuildMenuTest extends TestCase
{
    public function testGenerateBaseMenu()
    {
        $array = [
            '/'        => 'Home',
            '/about'   => 'About',
            '/contact' => 'Contact',
        ];

        $menu = Menu::generate('new', $array);

        $this->assertWithStub($menu->render());
    }

    public function testGenerateMenuFromCollection()
    {
        $collection = collect([
            ['title' => 'Home', 'slug' => 'home'],
            ['title' => 'About', 'slug' => 'about'],
            ['title' => 'Contact', 'slug' => 'contact'],
        ]);


        $menu = Menu::generate('new', $collection, function (MenuSupport $menu, array $item) {
            $menu->add(Item::new($item['slug'], $item['title']));
        });

        $this->assertWithStub($menu->render());
    }
}
