<?php
namespace app\admin\controller;
use think\Controller;

class Approvepartsconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function approvepartsconfirmorder(){

        return $this->fetch();
    }

    public function getexamineorder(){
    	$page = $_GET['page'];
    	$limit = $_GET['limit'];
        $user = session("user_session");
        $user_id = $user["user_id"];
        $orderType = 5;
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