<?php
namespace app\admin\controller;
use think\Controller;

class Addrepairconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addrepairconfirmorder(){
    	return $this->fetch();
    }
}