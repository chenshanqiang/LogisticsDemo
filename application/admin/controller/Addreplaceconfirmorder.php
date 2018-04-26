<?php
namespace app\admin\controller;
use think\Controller;
class Addreplaceconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addreplaceconfirmorder()
    {
        $companytable = \app\index\model\Admin::querydepartmentinfo(0);
        if(!empty($companytable))
            $this->assign("companylist",$companytable);
    	return $this->fetch();
    }

    public function editorder()
    {

    }

    public function getdepartmentinfo()
    {
        $param = $_POST;
        $result = \app\index\model\Admin::querydepartmentinfo($param['param']);
        return $result;

    }
}