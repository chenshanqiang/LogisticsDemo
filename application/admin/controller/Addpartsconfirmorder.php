<?php
namespace app\admin\controller;
use think\Controller;

class Addpartsconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addpartsconfirmorder(){
    	return $this->fetch();
    }
}