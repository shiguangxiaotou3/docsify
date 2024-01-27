<?php

namespace Shiguangxiaotou3\Docsify\helpers;


class ReplaceArrayValue
{

    public $value;


    public function __construct($value)
    {
        $this->value = $value;
    }


    public static function __set_state($state)
    {
        if (!isset($state['value'])) {
            throw new \Exception('Failed to instantiate class "ReplaceArrayValue". Required parameter "value" is missing');
        }

        return new self($state['value']);
    }
}