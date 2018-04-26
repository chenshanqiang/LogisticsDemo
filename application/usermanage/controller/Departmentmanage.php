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
        return $this->fetch();
    }
}