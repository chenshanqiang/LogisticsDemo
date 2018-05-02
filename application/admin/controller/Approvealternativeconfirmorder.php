<?php
namespace app\admin\controller;
use think\Controller;

class Approvealternativeconfirmorder extends Controller
{
	/*审核订单渲染方法*/
    public function approvealternativeconfirmorder(){

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