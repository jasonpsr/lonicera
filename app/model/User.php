<?php

namespace app\model;

use lonicera\core\Model;

class User extends Model
{
    public $id;
    public $age;
    public $name;

    protected $rule = ['pk' => 'id', 'plStrategy' => 'generator'];
}