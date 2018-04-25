<?php
namespace app\manager\controller;
use think\Controller;

class Addgoodsconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addgoodsconfirmorder(){
    	return $this->fetch();
    }
}