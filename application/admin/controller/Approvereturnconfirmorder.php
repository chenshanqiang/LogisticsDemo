<?php
namespace app\admin\controller;
use think\Controller;

class Approvereturnconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function approvereturnconfirmorder(){
    	return $this->fetch();
    }

    public function getexamineorder(){
    	$page = $_GET['page'];
    	$limit = $_GET['limit'];

    	if(isset($_GET['queryInfo'])){
    		$queryInfo = $_GET['queryInfo'];
    		$tablelist = \app\index\model\Admin::queryexaminegoodsorder($page,$limit,$queryInfo);
    	}else{
    		$tablelist = \app\index\model\Admin::queryexaminegoodsorder($page,$limit);
    	}
        
    	return $tablelist;
    }
}