<?php

namespace Tests\Unit;

use IsakzhanovR\Menus\Facades\Menu;
use IsakzhanovR\Menus\Support\Html;
use IsakzhanovR\Menus\Support\Item;
use Tests\TestCase;

class HtmlAttributesTest extends TestCase
{
    public function testAppendMenuAttributesBasic()
    {
        $item = Item::new('/', 'Main')
            ->addAttribute('class', 'active')
            ->addLinkAttribute('class', 'active-link')
            ->addParentAttribute('server-side-rendering')
            ->addParentAttribute('class', 'active-menu')
            ->addBefore('<span>Before - a</span>');

        $menu = Menu::make('new')
            ->addAttributes(['class' => 'main-nav']) // Duplicate class name
            ->addAttribute('data-attribute', json_encode(['title' => 'Title', 'menu' => 'New']))
            ->addClass(['main-nav', 'nav-menu'])
            ->addBefore(Html::new('<h2>Menu</h2>'))
            ->addAfter('<hr/>')
            ->add($item);

        $this->assertWithStub($menu->render());

    }

    public function testAppendSubmenuBasic()
    {
        $menu = Menu::make('main')
            ->setWrapperTag(null)
            ->setParentTag('div')
            ->add(Item::new('/', 'Main',))
            ->add(Item::new('/about', 'About'))
            ->add(Item::new('/contacts', 'Contacts'))
            ->submenu('dropdown', function (\IsakzhanovR\Menus\Support\Menu $menu) {
                $menu
                    ->addParentAttribute('id', 'dropdown')
                    ->addClass('dropdown-1')
                    ->setParentTag('div')
                    ->setWrapperTag('nav')
                    ->add(Item::new('/', 'Main'))
                    ->add(Item::new('/about', 'About'))
                    ->add(Item::new('/contacts', 'Contacts'))
                    ->submenu('subdropdown', function (\IsakzhanovR\Menus\Support\Menu $menu) {
                        $menu->addParentAttribute('class', 'subdropdown')
                            ->add(Item::new('/', 'Main'))
                            ->add(Item::new('/about', 'About')->addParentAttribute('class', 'about-3'))
                            ->add(Item::new('/contacts', 'Contacts'));
                    });
            });


        $this->assertWithStub($menu->render());
    }
}
