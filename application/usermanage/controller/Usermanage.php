<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 10:04
 */

namespace app\UserManage\controller;
use think\Controller;
use think\Request;

class Usermanage extends Controller
{
    public function usermanage(){
        return $this->fetch();
    }

    public function getusermanage()
    {
        $page = $_GET['page'];
        $limit = $_GET['limit'];
        $tablelist = \app\index\model\Admin::queryuserinfo($page,$limit);
        return $tablelist;
    }

    public function adduser()
    {
        $param = Request::instance()->param();
        //dump($param['param']);
        $user = $param['param'];
        $result = \app\index\model\Admin::adduser($user);
        return $result;
    }


}