<?php

namespace IsakzhanovR\Menus\Helpers;

class Tag
{
    public static function withContent($content, string $tag = null, string $attributes = ''): string
    {
        if (is_array($content)) {
            $content = implode(' ', $content);
        }

        return static::open($tag, $attributes) . $content . static::close($tag);

    }

    public static function open(string $tag = null, string $attributes = ''): string
    {
        if (is_null($tag)) {
            return '';
        }
        if (is_null($attributes)) {
            return "<{$tag}>";
        }

        return "<{$tag} {$attributes}>";
    }

    public static function close(string $tag = null): string
    {
        return $tag ? "</{$tag}>" : '';
    }
}
