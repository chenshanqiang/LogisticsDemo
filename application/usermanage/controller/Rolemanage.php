<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 17:17
 */

namespace app\usermanage\controller;

use think\Request;

use think\Controller;

class Rolemanage extends Controller
{
    public function rolemanage()
    {
        $roletable = \app\index\model\Admin::queryroleinfo();
        if(!empty($roletable))
            $this->assign("rolelist",$roletable);
        return $this->fetch();
    }

    public function getrolepermission()
    {
        $param = Request::instance()->param();
        $result = \app\index\model\Admin::queryroleinfo($param['param']);
        if(!empty($result))
            return $result[0];
        else {
            return null;
        }
    }

    public function setrolepermisson()
    {
        $param = Request::instance()->param();
        $result = \app\index\model\Admin::updateroleinfo($param['param']);
        return "$result";
    }
}