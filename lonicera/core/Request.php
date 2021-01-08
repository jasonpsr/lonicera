<?php


namespace lonicera\core;


class Request
{
    /**
     * 获取指定参数的值
     * @param $param
     * @return mixed|null
     */
    public function getParam($param)
    {
        if (isset($_REQUEST[$param])) {
            return $_REQUEST[$param];
        } else {
            return null;
        }
    }

    /**
     * 获取指定参数的值，并转为数组
     * @param $param
     * @return int
     */
    public function getInt($param)
    {
        return intval($this->getParam($param));
    }
}