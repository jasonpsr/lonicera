<?php


namespace lonicera\core;


class BaseException extends \Exception
{
    public function __toString()
    {
        return self::getMessage();
    }

    protected function _Log()
    {
        $err = date('Y-m-d H:i:s') . '|';
        $err .= '异常信息：' . self::getMessage() . '|';
        $err .= '异常码：' . self::getCode() . '|';
        $err .= '堆栈回溯：' . serialize(debug_backtrace()) . PHP_EOL;
        file_put_contents(_APP . 'log.txt', $err, FILE_APPEND);
    }

    public function errorMessage()
    {
        self::_Log();
    }
}