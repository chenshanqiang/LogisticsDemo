<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 17:11
 */

namespace app\usermanage\controller;
use think\Request;


use think\Controller;

class Adduser extends Controller
{
    public function adduser()
    {
        $roletable = \app\index\model\Admin::queryroleinfo();
        if(!empty($roletable))
            $this->assign("rolelist",$roletable);
        $jobtable = \app\index\model\Admin::queryjobinfo();
        if(!empty($jobtable))
            $this->assign("joblist",$jobtable);
        $companytable = \app\index\model\Admin::querydepartmentinfo(0);
        if(!empty($companytable))
            $this->assign("companylist",$companytable);
        return $this->fetch();
    }

    public function getdepartmentinfo()
    {
        $param = Request::instance()->param();
        $result = \app\index\model\Admin::adduser($param['param']);
        return $result;

    }

}