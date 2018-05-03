<?php
namespace app\admin\controller;
use think\Controller;
class Addreplaceconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addreplaceconfirmorder()
    {
        $depid = '假的id';  //部门id                                                       //假    改 。。。。。。。。。。。。。。
        $this->assign("depid",$depid);
        $date = date('Ymd');
        $this->assign("date",$date);
        $orderid = $date.'0001';                                                  //假    改 。。。。。。。。。。。。。。
        $this->assign("orderid",$orderid);
        $companytable = \app\index\model\Admin::querydepartmentinfo(0);
        if(!empty($companytable))
            $this->assign("companylist",$companytable);
    	return $this->fetch();
    }

    public function addconfirmorder()
    {
        $cs_info = $_POST['cs_info'];
        $custom_info = $_POST['custom_info'];
        $delivery_info = $_POST['delivery_info'];
        $return_info = $_POST['return_info'];
        $payment_info = $_POST['payment_info'];
        $cs_belong = $_POST['cs_belong'];
        $custom_info_id = \app\index\model\Admin::getmaxtableid('custom_info','custom_info_id');
        $custom_info_id = $custom_info_id + 1;
        $delivery_info_id = \app\index\model\Admin::getmaxtableid('delivery_info','delivery_info_id');
        $delivery_info_id = $delivery_info_id + 1;
        $payment_info_id = \app\index\model\Admin::getmaxtableid('payment_info','payment_info_id');
        $payment_info_id = $payment_info_id + 1;
        $return_info_id = \app\index\model\Admin::getmaxtableid('return_info','return_info_id');
        $return_info_id = $return_info_id + 1;
        $retsql = \app\index\model\Admin::addcustominfo($custom_info);
        if (empty($retsql)){
            return null;
        }
        $retsql = \app\index\model\Admin::adddeliveryinfo($delivery_info);
        if (empty($retsql)){
            \app\index\model\Admin::deleterowtableid('custom_info','custom_info_id',$custom_info_id);
            return null;
        }
        $retsql = \app\index\model\Admin::addreturninfo($return_info);
        if (empty($retsql)){
            \app\index\model\Admin::deleterowtableid('delivery_info','delivery_info_id',$delivery_info_id);
            return null;
        }
        $retsql = \app\index\model\Admin::addpaymentinfo($payment_info);
        if (empty($retsql)){
            \app\index\model\Admin::deleterowtableid('return_info','return_info_id',$return_info_id);
            return null;
        }

        $cs_info['custom_info_id'] = $custom_info_id;
        $cs_info['delivery_info_id'] = $delivery_info_id;
        $cs_info['payment_info_id'] = $payment_info_id;
        $retsql = \app\index\model\Admin::addconfirmorder($cs_info);
        if (!empty($retsql)){
            $cs_belong['cs_id'] = $cs_info['cs_id'];
            $cs_belong['cs_belong_create_time'] = date("Y-m-d H:i:s");
            $retsql = \app\index\model\Admin::addbcsbelong($cs_belong);
        }else{
            \app\index\model\Admin::deleterowtableid('custom_info','custom_info_id',$custom_info_id);
            \app\index\model\Admin::deleterowtableid('delivery_info','delivery_info_id',$delivery_info_id);
            \app\index\model\Admin::deleterowtableid('return_info','return_info_id',$return_info_id);
            \app\index\model\Admin::deleterowtableid('payment_info','payment_info_id',$delivery_info_id);
        }
        return $retsql;
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