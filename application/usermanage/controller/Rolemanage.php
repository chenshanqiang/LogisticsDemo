<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 17:17
 */

namespace app\usermanage\controller;


use think\Controller;

class Rolemanage extends Controller
{
    public function rolemanage()
    {
        return $this->fetch();
    }
}