<?php


namespace app\front\module;


use lonicera\core\InterceptorInterface;

class LoginInterceptor implements InterceptorInterface
{

    public function preHandle()
    {
        echo 'LoginInterceptor..preHandle()';
    }

    public function postHandle()
    {
        return true;
    }
}