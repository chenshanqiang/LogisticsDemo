<?php

namespace app\admin\controller;

use think\Controller;
use think\Exception;

class Addreplaceconfirmorder extends Controller
{
    /*新增订单渲染方法*/
    public function addreplaceconfirmorder()
    {
        $organizeid = session("user_session");
        $depid = $organizeid["organize_id"];  //部门id
        $this->assign("depid", $depid);
        $date = date('Ymd');
        $this->assign("date", $date);
        /*$orderid = \app\index\model\Admin::getcsinfomaxid();
        $this->assign("orderid", $orderid);*/
        $companytable = \app\index\model\Admin::querydepartmentinfo(0);
        if (!empty($companytable))
            $this->assign("companylist", $companytable);
        return $this->fetch();
    }

    /**新增订单（包含审批 清单）**/
    public function addconfirmorder()
    {
        $cs_info = $_POST['cs_info'];
        $custom_info = $_POST['custom_info'];
        $delivery_info = $_POST['delivery_info'];
        $return_info = $_POST['return_info'];
        $cs_belong = $_POST['cs_belong'];
        $cs_examine = $_POST['cs_examine'];
        $order_goods_manager = $_POST['order_goods_manager'];

        $cs_info_id = \app\index\model\Admin::getcsinfomaxid();
        $cs_info['cs_id'] = $cs_info_id;
        $cs_belong['cs_id'] = $cs_info['cs_id'];
        $cs_belong['cs_belong_create_time'] = date("Y-m-d H:i:s");

        $retsql = \app\index\model\Admin::addcsbelong($cs_belong);

        $retsql = \app\index\model\Admin::addcustominfo($custom_info);
        if (empty($retsql)) {
            return null;//添加失败删除    cs_belong
        }
        $retsql = \app\index\model\Admin::adddeliveryinfo($delivery_info);
        if (empty($retsql)) {
            \app\index\model\Admin::deleterowtableid('custom_info', 'custom_info_id', $custom_info_id);
            return null;
            return '-------------------52----------------------';
        }
        $retsql = \app\index\model\Admin::addreturninfo($return_info);
        if (empty($retsql)) {
            \app\index\model\Admin::deleterowtableid('delivery_info', 'delivery_info_id', $delivery_info_id);
            return null;
            return '------------------58-----------------------';
        }
        $num = count($order_goods_manager);
        for ($i = 0; $i < $num; $i++) {
            $order_goods_manager[$i]['cs_id'] = $cs_info['cs_id'];
            $retmanager = \app\index\model\Admin::addordergoodsmanager($order_goods_manager[$i]);
        }
        $length = count($cs_examine);
        for ($i = 0; $i < $length; $i++) {
            $cs_examine[$i]['cs_id'] = $cs_info['cs_id'];
            $user_id = $cs_examine[$i]['submit_user_id'];
            if ($i == 0) {
                $dbleader = \app\index\model\Admin::getdepleaderbyuserid($user_id, '总监');
                if (empty($dbleader)) {
                    return '总监';
                }
                $cs_examine[$i]['examine_user_id'] = $dbleader[0]['user_id'];
                $cs_examine[$i]['cs_examine_name'] = $dbleader[0]['fullname'];
            } else if ($i == 1) {
                $dbleader = \app\index\model\Admin::getdepleaderbyuserid($user_id, '总经理');
                if (empty($dbleader)) {
                    return '总经理';
                    //return false;
                }
                $cs_examine[$i]['examine_user_id'] = $dbleader[0]['user_id'];
                $cs_examine[$i]['cs_examine_name'] = $dbleader[0]['fullname'];
            } else if ($i == 2) {
                $dbleader = \app\index\model\Admin::getdepleaderbyuserid($user_id, '财务部');
                if (empty($dbleader)) {
                    return '财务部';
                    return false;
                }
                $cs_examine[$i]['examine_user_id'] = $dbleader[0]['user_id'];
                $cs_examine[$i]['cs_examine_name'] = $dbleader[0]['fullname'];
            }
            $rettest = \app\index\model\Admin::addcsexamine($cs_examine[$i]);
        }

        $custom_info_id = \app\index\model\Admin::getmaxtableidretid('custom_info', 'custom_info_id');
        $delivery_info_id = \app\index\model\Admin::getmaxtableidretid('delivery_info', 'delivery_info_id');
        $cs_examine_id = \app\index\model\Admin::getmaxtableidretid('cs_examine', 'cs_examine_id');
        $return_info_id = \app\index\model\Admin::getmaxtableidretid('return_info', 'return_info_id');

        if ($return_info_id == -1 || $custom_info_id == -1 || $delivery_info_id == -1 || $cs_examine_id == -1) {
            return false;
        }
        $cs_info['return_info_id'] = $return_info_id;
        $cs_info['custom_info_id'] = $custom_info_id;
        $cs_info['delivery_info_id'] = $delivery_info_id;
        $cs_info['cs_examine_ids'] = ($cs_examine_id - 3) . ',' . ($cs_examine_id - 2) . ',' . ($cs_examine_id - 1);
        $retsql = \app\index\model\Admin::addconfirmorder($cs_info);
        if (!empty($retsql)) {
            //删除上面的表
        } else {
            return '订单写入出错';
            \app\index\model\Admin::deleterowtableid('custom_info', 'custom_info_id', $custom_info_id);
            \app\index\model\Admin::deleterowtableid('delivery_info', 'delivery_info_id', $delivery_info_id);
            \app\index\model\Admin::deleterowtableid('return_info', 'return_info_id', $return_info_id);
            //\app\index\model\Admin::deleterowtableid('payment_info','payment_info_id',$delivery_info_id);
        }
        return '成功了！！！！';
        return $retsql;
    }


    public function getdepartmentinfo()
    {
        $param = $_POST;
        $result = \app\index\model\Admin::querydepartmentinfo($param['param']);
        return $result;
    }

    public function getdspmanagerinfo()
    {
        $dep_id = $_POST['param'];
        $result = \app\index\model\Admin::getuserinfobydepid($dep_id);
        return $result;
    }
}