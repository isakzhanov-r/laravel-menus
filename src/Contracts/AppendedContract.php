<?php

namespace IsakzhanovR\Menus\Contracts;

interface AppendedContract
{
    /**
     * @return string
     */
    public function render(): string;

    /**
     * @return mixed
     */
    public function store();
}
