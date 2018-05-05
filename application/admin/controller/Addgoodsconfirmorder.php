<?php
namespace app\admin\controller;
use think\Controller;
use think\Input;

class Addgoodsconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addgoodsconfirmorder(){
        $date = date('Ymd');
        $this->assign("date", $date);
    	return $this->fetch();
    }
}