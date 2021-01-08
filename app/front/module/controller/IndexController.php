<?php

namespace app\front\module\controller;

use app\model\User;
use lonicera\core\Controller;

class IndexController extends Controller
{
    public function _before_()
    {
        echo '_before_';
    }

    public function _after_()
    {
        echo '_after_';
    }

    public function indexAction()
    {
        $user = new User();
        $user->age = 20;
        $user->name = 'baicai';
        $user->save();
        $this->assign('age', $user->age + 1);
        $this->display();
    }

    public function hiAction()
    {
        /*require_once _SYS_PATH . 'core/Db.php';
        $db = DB::getInstance($GLOBALS['_config']['db']);
        $ret = $db->query('select * form a where id=:id', ['id' => 1]);
        var_dump($ret);
        echo 'hiAction';*/
        require_once _SYS_PATH . 'core/Model.php';
        require_once _APP . 'model/User.php';
        $user = new User();
        $user->age = 20;
        $user->name = 'baicai';
        $user->save();
    }

    public function updateAction()
    {
        echo 'updateAction';
    }
}