<?php


namespace library\container;


class Container extends ContainerAccess
{
    protected $inject = []; // 存放给bean注入的方法
    protected $instance = []; // 对象存储的数组


}