<?php
namespace app\admin\controller;
use think\Controller;

class Approveborrowconfirmorder extends Controller
{
	/*审核订单渲染方法*/
    public function approveborrowconfirmorder(){
        $organizeid = session("user_session");
        $tablelist = \app\index\model\Admin::querydepartmentinfo($organizeid["organize_id"]);
        if(!empty($tablelist))
            $this->assign("departmentlist",$tablelist);
        else
        {
            $tablelist = \app\index\model\Admin::querydepartmentinfo($organizeid["organize_id"],$organizeid["organize_id"]);
            if(!empty($tablelist))
                $this->assign("departmentlist",$tablelist);
        }
        return $this->fetch();
    }

    public function getexamineorder(){
    	$page = $_GET['page'];
    	$limit = $_GET['limit'];
        $user_id = 1;
        $orderType = 2;
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