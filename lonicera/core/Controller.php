<?php

namespace lonicera\core;

use library\render\PhpRender;

class Controller
{
    private $db;
    private $view;
    protected static $route;
    protected $request;

    public function __construct()
    {
        $this->view = new PhpRender();
        $this->request = new Request();
    }

    protected function assign($key, $value)
    {
        $this->view->assign($key, $value);
        return $this->view;
    }

    public function db($conf = array())
    {
        if ($conf == NULL) {
            $conf = $GLOBALS['_config']['db'];
        }
        $this->db = Db::getInstance($conf);
        return $this->db;
    }

    public function display($file = "")
    {
        if (func_num_args() == 0 || $file == NULL) {
            $control = self::$route->control;
            $action = self::$route->action;
            $viewFilePath = _ROOT . 'app/' . self::$route->group . '/module/view/';
            $viewFilePath .= $control . DIRECTORY_SEPARATOR . $action . '.php';
        } else {
            $viewFilePath = $file . '.php';
        }
        $this->view->display($viewFilePath);
    }

    public function fetch($file = "")
    {
        ob_start();
        ob_implicit_flush();
        $this->display($file);
        $contents = ob_get_clean();
        return $contents;
    }
}