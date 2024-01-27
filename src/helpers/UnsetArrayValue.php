<?php

namespace Shiguangxiaotou3\Docsify\helpers;

class UnsetArrayValue
{

    public static function __set_state($state)
    {
        return new self();
    }
}