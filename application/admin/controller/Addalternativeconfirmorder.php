<?php
namespace app\admin\controller;
use think\Controller;

class Addalternativeconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addalternativeconfirmorder(){
    	return $this->fetch();
    }
}