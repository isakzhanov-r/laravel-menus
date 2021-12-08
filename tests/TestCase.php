<?php

namespace Tests;

use Illuminate\Support\Str;
use IsakzhanovR\Menus\ServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return
            [
                ServiceProvider::class,
            ];
    }

    protected function assertWithStub(string $actual)
    {
        $this->assertEquals(
            $this->sanitizeHtmlWhitespace($this->getStubForTest()),
            $this->sanitizeHtmlWhitespace($actual)
        );
    }

    protected function getStubForTest()
    {
        $title = str_replace('test', '', $this->getName());
        $title = $this->fromCamelCase($title);
        $title = str_replace('-', '.', Str::slug($title));


        return file_get_contents(__DIR__ . "./stubs/$title.stub");
    }

    protected function fromCamelCase(string $camelCaseString)
    {
        $re = '/(?<=[a-z])(?=[A-Z])/x';
        $a  = preg_split($re, $camelCaseString);

        return join($a, " ");
    }

    protected function sanitizeHtmlWhitespace(string $subject): string
    {
        $find    = ['/>\s+</', '/(^\s+)|(\s+$)/'];
        $replace = ['><', ''];

        return preg_replace($find, $replace, $subject);
    }
}
