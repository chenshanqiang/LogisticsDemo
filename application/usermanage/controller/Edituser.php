<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27
 * Time: 11:39
 */

namespace app\usermanage\controller;


use think\Controller;

class Edituser extends Controller
{
    public function edituser()
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
}