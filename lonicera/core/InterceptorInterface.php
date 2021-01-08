<?php


namespace lonicera\core;


interface InterceptorInterface
{
    /**
     * 前置拦截器，在所有 Action 运行前会进行拦截
     * @return mixed
     */
    public function preHandle();

    /**
     * 后置拦截器
     * @return mixed
     */
    public function postHandle();
}