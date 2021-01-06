<?php

namespace library\render;

use lonicera\core\Render;

class PhpRender implements Render
{
    private $value = array();

    public function init()
    {
        // TODO: Implement init() method.
    }

    public function assign($key, $value)
    {
        $this->value[$key] = $value;
    }

    public function display($view = '')
    {
        extract($this->value);
        include $view;
    }
}