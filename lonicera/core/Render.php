<?php

namespace lonicera\core;

interface Render
{
    public function init();

    public function assign($key, $value);

    public function display($view = '');

}