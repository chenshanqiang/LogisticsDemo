<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 10:04
 */

namespace app\UserManager\controller;


use think\Controller;

class UserManange extends Controller
{
    public function UserManage(){
        return $this->fetch();
    }
}