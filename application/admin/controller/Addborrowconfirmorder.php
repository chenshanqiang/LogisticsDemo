<?php
namespace app\admin\controller;
use think\Controller;

class Addborrowconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addborrowconfirmorder(){
    	return $this->fetch();
    }
}