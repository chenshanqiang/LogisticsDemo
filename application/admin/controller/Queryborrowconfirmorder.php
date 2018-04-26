<?php
namespace app\admin\controller;
use think\Controller;
use think\Input;

class Queryborrowconfirmorder extends Controller
{
	/*查询订单渲染方法*/
    public function queryborrowconfirmorder(){
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