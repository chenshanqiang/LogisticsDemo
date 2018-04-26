<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 17:18
 */

namespace app\usermanage\controller;

use think\Controller;

class Departmentmanage extends Controller
{
    public function departmentmanage()
    {
//        $roletable = \app\index\model\Admin::queryroleinfo();
//        if(!empty($roletable))
//            $this->assign("rolelist",$roletable);
        return $this->fetch();
    }
}