<?php

namespace Settings;

enum Map: string
{
    case THEME = 'dark';
    case TIMEZONE = 'MSK';

    public static function fromName(string $name): self
    {
        $name = mb_strtoupper($name);
        return match(true) {
            $name === self::THEME->name => self::THEME,
            $name === self::TIMEZONE->name => self::TIMEZONE,
        };
    }
}
