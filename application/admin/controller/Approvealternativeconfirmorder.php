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
        $user = session("user_session");
    	$user_id = $user["user_id"];
    	$orderType = 6;
    	if(isset($_GET['queryInfo'])){
    		$queryInfo = $_GET['queryInfo'];

    		$tablelist = \app\index\model\Admin::queryApproveConfirmOrder($user_id,$orderType,$page,$limit,$queryInfo);
    	}else{
    		$tablelist = \app\index\model\Admin::queryApproveConfirmOrder($user_id,$orderType,$page,$limit);
    	}
        
    	return $tablelist;
    }

    public function getareamanager(){
        $departmentId = $_POST;
        $organizeID = \app\index\model\Admin::getuserinfobydepid($departmentId["param"]);
        return $organizeID;
    }
}