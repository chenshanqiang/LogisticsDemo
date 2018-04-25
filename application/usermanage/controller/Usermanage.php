<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 10:04
 */

namespace app\UserManage\controller;
use think\Controller;

class Usermanage extends Controller
{
    public function usermanage(){
        return $this->fetch();
    }
}