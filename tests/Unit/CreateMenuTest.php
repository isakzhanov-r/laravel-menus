<?php

namespace Tests\Unit;

use IsakzhanovR\Menus\Facades\Menu;
use IsakzhanovR\Menus\Support\Html;
use IsakzhanovR\Menus\Support\Item;
use Tests\TestCase;

class CreateMenuTest extends TestCase
{
    public function testCreateBaseMenu()
    {
        $menu = Menu::make('new')
            ->link('/', 'Main')
            ->link('/about', 'About')
            ->link('/contacts', 'Contacts');


        $this->assertWithStub($menu->render());
    }

    public function testCreateMenuAddingItem()
    {
        $menu = Menu::make('new')
            ->add(Item::new('/', 'Main'))
            ->add(Item::new('/about', 'About'))
            ->add(Item::new('/contacts', 'Contacts'))
            ->add(Html::new('<li><a href="/html">HTML</a></li>'));

        $this->assertWithStub($menu->render());
    }
}
