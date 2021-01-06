<?php

return [
    'mode'              => 'debug', // 是否调试模式
    'filter'            => true,    // 是否过滤请求
    'charSet'           => 'utf-8',
    'defaultApp'        => 'front',
    'defaultController' => 'index',
    'defaultAction'     => 'index',
    'UrlControllerName' => 'c',
    'UrlActionName'     => 'a',
    'UrlGroupName'      => 'g',
    'db'                => [
        'dsn'      => 'mysql:host=localhost;dbname=test',
        'username' => 'root',
        'password' => 'root',
        'prefix'   => '',
        'param'    => [],
    ],
    'smtp'              => [],
];