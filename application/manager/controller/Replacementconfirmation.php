<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/04/25
 * Time: 19:14
 */

namespace app\manager\controller;
use think\Controller;
use think\Exception;
use  think\Request;
use app\manager\model\Addconfirmorder;

class Replacementconfirmation extends  Controller
{
    public function replacementconfirmation()
    {
        $dep_info = \app\index\model\Admin::querydepartmentinfo();
        if(!empty($dep_info))
            $this->assign("companylist",$dep_info);
        //$userinfo = \app\index\model\Admin::getloginuserinfo()
        //var_dump('******************1');
        return $this->fetch();
    }

    public function  editorder()
    {
        $data = $_GET['data'];

    }
}