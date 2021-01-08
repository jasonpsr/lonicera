<?php


namespace app\front\module;


use lonicera\core\InterceptorInterface;

class PayInterceptor implements InterceptorInterface
{

    public function preHandle()
    {
        echo 'PayInterceptor..preHandle()';
    }

    public function postHandle()
    {
        return true;
    }
}