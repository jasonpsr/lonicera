<?php

use lonicera\core\Route;

class Lonicera
{
    private $route;

    public function run()
    {
        require_once _SYS_PATH . 'Loader.php';
        spl_autoload_register(array('Loader', 'loadLibClass'));
        $this->route();
        $this->dispatch();

        if ('debug' == $GLOBALS['_config']['mode']) {
            // 如果是调试/开发模式，打开警告输出
            if (substr(PHP_VERSION, 0, 3) >= "5.5") {
                error_reporting(E_ALL);
            } else {
                error_reporting(E_ALL | E_STRICT);
            }
        } else {
            // 生产模式关闭所有错误报告，将错误报告重定向到文件
            set_error_handler(['Lonicera', 'errorHandler']);
        }
        set_exception_handler(['Lonicera', 'errorHandler']);
    }

    public function route()
    {
        $this->route = new Route();
        $this->route->init();
    }

    public function dispatch()
    {
        $controlName = ucfirst($this->route->control) . 'Controller';
        $actionName = $this->route->action . 'Action';
        $group = $this->route->group;
        $className = "app\\{$group}\module\controller\\{$controlName}";
        $methods = get_class_methods($className);

        if (!in_array($actionName, $methods, TRUE)) {
            throw new \Exception(sprintf('方法名 % s-> % s 不存在或非 public', $controlName, $actionName));
        }
        $handler = new $className();

        $reflectedClass = new \ReflectionClass('lonicera\core\Controller');
        $reflectedProperty = $reflectedClass->getProperty('route');
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($this->route);

        $this->handleInterceptor('preHandle');
        if (in_array('_before_', $methods)) {
            call_user_func(array($handler, '_before_'));
        }
        $handler->{$actionName}();
        if (in_array('_after_', $methods)) {
            call_user_func(array($handler, '_after_'));
        }
        $this->handleInterceptor('postHandle');
    }

    public static function exceptionHandler($exception)
    {
        if ($exception instanceof \lonicera\core\BaseException) {
            $exception->errorMessage();
        } else {
            $newException = new \lonicera\core\BaseException('未知异常', 2000, $exception);
            $newException->getMessage();
        }
    }

    public static function errorHandler($errNo, $errStr, $errFile, $errLine)
    {
        $err = '错误级别: ' . $errNo . '|错误描述: ' . $errStr;
        $err .= '|错误所在文件: ' . $errFile . '|错误所在行号:' . $errLine . "\r\n";
        echo $err;
        file_put_contents(_APP . 'log.txt', $err, FILE_APPEND);
    }

    public function handleInterceptor($type)
    {
        $interceptorArr = $GLOBALS['_config']['interceptorArr'];
        // 后置方法需要反向调用
        if ($type == 'postHandle') {
            $interceptorArr = array_reverse($interceptorArr);
        }
        $path = "{$this->route->group}/{$this->route->control}/{$this->route->action}";
        foreach ($interceptorArr as $key => $value) {
            // 匹配是否要调用拦截器
            if ($value == '*' || preg_match($value, $path) > 0) {
                $interceptor = new $key;
                $interceptor->{$type}();
            }
        }
    }
}