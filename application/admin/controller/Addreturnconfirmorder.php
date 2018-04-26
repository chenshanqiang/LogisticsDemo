<?php
namespace app\admin\controller;
use think\Controller;

class Addreturnconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addreturnconfirmorder(){
    	return $this->fetch();
    }
}