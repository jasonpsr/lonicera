<?php

define('_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('_SYS_PATH', _ROOT . 'lonicera' . DIRECTORY_SEPARATOR);
define('_APP', _ROOT . 'app' . DIRECTORY_SEPARATOR);
define('_VERSION', '0.1');
$GLOBALS['_config'] = require _SYS_PATH . 'config.php';
// Autoload自动载入
require _ROOT . 'vendor/autoload.php';
require _SYS_PATH . 'Lonicera.php';
$app = new Lonicera();
$app->run();