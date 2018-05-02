<?php
namespace app\admin\controller;
use think\Controller;
class Addreplaceconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addreplaceconfirmorder()
    {
        $depid = '假的id';  //部门id                                                       //假    改 。。。。。。。。。。。。。。
        $this->assign("orderid",$depid);
        $date = date('Ymd');
        $this->assign("date",$date);
        $orderid = $date.'0001';                                                  //假    改 。。。。。。。。。。。。。。
        $this->assign("orderid",$orderid);
        $companytable = \app\index\model\Admin::querydepartmentinfo(0);
        if(!empty($companytable))
            $this->assign("companylist",$companytable);
    	return $this->fetch();
    }

    public function editorder()
    {
        $data = $_POST['data'];
        $cs_id = $data['swiftnumber'];
        $cur_process_user_id = '';                                          //假    改 。。。。。。。。。。。。。。
        //$cs_info_type
    }

    public function getdepartmentinfo()
    {
        $param = $_POST;
        $result = \app\index\model\Admin::querydepartmentinfo($param['param']);
        return $result;
    }

    public  function getdspmanagerinfo()
    {
        $dep_id = $_POST['param'];
        $result = \app\index\model\Admin::getuserinfobydepid($dep_id);
        return $result;
    }
}