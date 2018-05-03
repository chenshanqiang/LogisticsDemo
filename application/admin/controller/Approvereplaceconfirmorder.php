<?php
namespace app\admin\controller;
use think\Controller;

class Approvereplaceconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function approvereplaceconfirmorder(){
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

        /*获取待审核订货确认单*/
    public function getexamineorder(){
    	$page = $_GET['page'];
    	$limit = $_GET['limit'];
        $user_id = 1;
        $orderType = 1;
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