<?php
namespace app\admin\controller;
use think\Controller;

class Addreplaceconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addreplaceconfirmorder(){
    	return $this->fetch();
    }
}