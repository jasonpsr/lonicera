<?php

namespace lonicera\core;

class Route
{
    public $group;
    public $control;
    public $action;
    public $params;

    public function __construct()
    {
    }

    public function init()
    {
        $route = $this->getRequest();
        $this->group = $route['group'];
        $this->control = $route['controll'];
        $this->action = $route['action'];
        !empty($route['param']) && $this->params = $route['param'];
    }

    public function getRequest()
    {
        $filter_param = array('<', '>', '"', "'", '% 3C', '% 3E', '% 22', '% 27', '% 3c', '% 3e');
        $uri = str_replace($filter_param, '', $_SERVER['REQUEST_URI']);
        $path = parse_url($uri);
        if (strpos($path['path'], 'index.php') == 0) {
            $urlR0 = $path['path']; // 处理urlrewrite后的URL，此时不带index.php
        } else {
            $urlR0 = substr($path['path'], strlen('index.php') + 1);
        }
        $urlR = ltrim($urlR0, '/');
        if ($urlR == '') {
            $route = $this->parseTradition();
            return $route;
        }
        $reqArr = explode('/', $urlR);
        foreach ($reqArr as $key => $value) {
            if (empty($value)) {
                unset($reqArr[$key]);
            }
        }
        $cnt = count($reqArr);
        if (empty($reqArr) || empty($reqArr[0])) {
            $cnt = 0;
        }
        switch ($cnt) {
            case 0:
                $route['group'] = $GLOBALS['_config']['defaultApp'];
                $route['controll'] = $GLOBALS['_config']['defaultController'];
                $route['action'] = $GLOBALS['_config']['defaultAction'];
                break;
            case 1:
                if (stripos($reqArr[0], ':')) {
                    $gc = explode(':', $reqArr[0]);
                    $route['group'] = $gc[0];
                    $route['controll'] = $gc[1];
                    $route['action'] = $GLOBALS['_config']['defaultApp'];
                } else {
                    $route['group'] = $GLOBALS['_config']['defaultApp'];
                    $route['controll'] = $reqArr[0];
                    $route['action'] = $GLOBALS['_config']['defaultAction'];
                }
                break;
            default:
                if (stripos($reqArr[0], ':')) {
                    $gc = explode(':', $reqArr[0]);
                    $route['group'] = $gc[0];
                    $route['controll'] = $gc[1];
                    $route['action'] = $reqArr[1];
                } else {
                    $route['group'] = $GLOBALS['_config']['defaultApp'];
                    $route['controll'] = $reqArr[0];
                    $route['action'] = $reqArr[1];
                }
                for ($i = 2; $i < $cnt; $i++) {
                    $route['param'][$reqArr[$i]] = isset($reqArr[++$i]) ? $reqArr[$i] : '';
                }
                break;

        }
        // 需要处理 query 字符串了
        if (!empty($path['query'])) {
            parse_str($path['query'], $routeQ);
            if (empty($route['param'])) {
                $route['param'] = array();
            }
            $route['param'] += $routeQ;
        }

        return $route;
    }

    /**
     * 解析传统形式的 URL
     * @return array
     */
    public function parseTradition()
    {
        $route = [];
        if (!isset($_GET[$GLOBALS['_config']['UrlGroupName']])) {
            $_GET[$GLOBALS['_config']['UrlGroupName']] = '';
        }
        if (!isset($_GET[$GLOBALS['_config']['UrlControllerName']])) {
            $_GET[$GLOBALS['_config']['UrlControllerName']] = '';
        }
        if (!isset($_GET[$GLOBALS['_config']['UrlActionName']])) {
            $_GET[$GLOBALS['_config']['UrlActionName']] = '';
        }
        $route['group'] = $_GET[$GLOBALS['_config']['UrlGroupName']];
        $route['controller'] = $_GET[$GLOBALS['_config']['UrlControllerName']];
        $route['action'] = $_GET[$GLOBALS['_config']['UrlActionName']];
        unset($_GET[$GLOBALS['_config']['UrlGroupName']]);
        unset($_GET[$GLOBALS['_config']['UrlControllerName']]);
        unset($_GET[$GLOBALS['_config']['UrlActionName']]);
        $route['param'] = $_GET;
        if ($route['group'] == NULL) {
            $route['group'] = $GLOBALS['_config']['defaultApp'];
        }
        if ($route['controller'] == NULL) {
            $route['controller'] = $GLOBALS['_config']['defaultController'];
        }
        if ($route['action'] == NULL) {
            $route['action'] = $GLOBALS['_config']['defaultAction'];
        }
        return $route;
    }
}