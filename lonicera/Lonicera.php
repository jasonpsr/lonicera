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

        $handler->{$actionName}();

        /*$controlName = $this->route->control . 'Controller';
        $actionName = $this->route->action . 'Action';
        $path = _APP . $this->route->group . DIRECTORY_SEPARATOR . 'module';
        $path .= DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $controlName . '.php';
        require_once $path;
        $methods = get_class_methods($controlName);
        if (!in_array($actionName, $methods, TRUE)) {
            throw new Exception(sprintf('方法名 % s-> % s 不存在或非 public', $controlName, $actionName));
        }
        $handler = new $controlName();

        $reflectedClass = new ReflectionClass('Controller');
        $reflectedProperty = $reflectedClass->getProperty('route');
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($this->route);

        $handler->{$actionName}();*/
    }
}