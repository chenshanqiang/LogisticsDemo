<?php

namespace app\admin\controller;

use think\Controller;
use think\Exception;

class Addreplaceconfirmorder extends Controller
{
    /*新增订单渲染方法*/
    public function addreplaceconfirmorder()
    {
        $depid = '假的id';  //部门id                                                       //假    改 。。。。。。。。。。。。。。
        $this->assign("depid", $depid);
        $date = date('Ymd');
        $this->assign("date", $date);
        $orderid = $date . '0002';                                                  //假    改 。。。。。。。。。。。。。。
        $this->assign("orderid", $orderid);
        $companytable = \app\index\model\Admin::querydepartmentinfo(0);
        if (!empty($companytable))
            $this->assign("companylist", $companytable);
        return $this->fetch();
    }

    /**新增订单（包含审批 清单）**/
    public function addconfirmorder()
    {
        //try {
            $cs_info = $_POST['cs_info'];
            $custom_info = $_POST['custom_info'];
            $delivery_info = $_POST['delivery_info'];
            $return_info = $_POST['return_info'];
            $cs_belong = $_POST['cs_belong'];
            $cs_examine = $_POST['cs_examine'];
            $order_goods_manager = $_POST['order_goods_manager'];
            //$payment_info = $_POST['payment_info'];
            //$order_goods_logistics = $_POST['order_goods_logistics'];
            //$payment_info_id = \app\index\model\Admin::getmaxtableid('payment_info','payment_info_id');
            //$payment_info_id = $payment_info_id + 1;
            //$order_goods_manager_id = \app\index\model\Admin::getmaxtableid('order_goods_manager','order_goods_manager_id');

            $custom_info_id = \app\index\model\Admin::getmaxtableidretid('custom_info', 'custom_info_id');
            $delivery_info_id = \app\index\model\Admin::getmaxtableidretid('delivery_info', 'delivery_info_id');
            $cs_examine_id = \app\index\model\Admin::getmaxtableidretid('cs_examine', 'cs_examine_id');
            $return_info_id = \app\index\model\Admin::getmaxtableidretid('return_info', 'return_info_id');
            if ($return_info_id == -1 || $custom_info_id == -1 || $delivery_info_id == -1 || $cs_examine_id == -1){
                return false;
            }
            //return $custom_info_id;
            $return_info_id = $return_info_id + 1;
            $custom_info_id = $custom_info_id + 1;
            $delivery_info_id = $delivery_info_id + 1;
            $retsql = \app\index\model\Admin::addcustominfo($custom_info);
            if (empty($retsql)) {
                return null;
            }
            $retsql = \app\index\model\Admin::adddeliveryinfo($delivery_info);
            if (empty($retsql)) {
                \app\index\model\Admin::deleterowtableid('custom_info', 'custom_info_id', $custom_info_id);
                return null;
            }
            $retsql = \app\index\model\Admin::addreturninfo($return_info);
            if (empty($retsql)) {
                \app\index\model\Admin::deleterowtableid('delivery_info', 'delivery_info_id', $delivery_info_id);
                return null;
            }

            /*$retsql = \app\index\model\Admin::addpaymentinfo($payment_info);
            if (empty($retsql)) {
                \app\index\model\Admin::deleterowtableid('return_info', 'return_info_id', $return_info_id);
                return null;
            }*/
            $cs_info['return_info_id'] = $return_info_id;
            $cs_info['custom_info_id'] = $custom_info_id;
            $cs_info['delivery_info_id'] = $delivery_info_id;
            //$cs_info['payment_info_id'] = $payment_info_id;
            $cs_info['cs_examine_ids'] = ($cs_examine_id + 1) . ',' . ($cs_examine_id + 2) . ',' . ($cs_examine_id + 3);
            $retsql = \app\index\model\Admin::addconfirmorder($cs_info);

            return $retsql;
            if (!empty($retsql)) {
                //$order_goods_manager['cs_id'] = $cs_info['cs_id'];
                $cs_belong['cs_id'] = $cs_info['cs_id'];
                $cs_belong['cs_belong_create_time'] = date("Y-m-d H:i:s");
                //\app\index\model\Admin::addordergoodsmanager($order_goods_manager);/**新增清单 缺product_info_id**/
                $retsql = \app\index\model\Admin::addbcsbelong($cs_belong);
                $num = count($order_goods_manager);
                for ($i = 0; $i < $num; $i++) {
                    $order_goods_manager[$i]['user_id'] = $cs_info['cs_id'];
                    $retmanager = \app\index\model\Admin::addordergoodsmanager($order_goods_manager[$i]);
                    /**新增清单 缺product_info_id**/
                    /*if (!empty($retmanager)){
                        $order_goods_manager_id = $order_goods_manager_id + 1;
                        //$order_goods_logistics[$i]['user_id'] = ;                                     //改   获取用户id
                        $order_goods_logistics[$i]['order_goods_manager_id'] = $order_goods_manager_id;
                        \app\index\model\Admin::addordergoodslogistics($order_goods_logistics[$i]);
                    }
                    else{
                        return '新增清单错误';
                    }*/
                }
                $length = count($cs_examine);
                for ($i = 0; $i < $length; $i++) {
                    $cs_examine[$i]['cs_id'] = $cs_info['cs_id'];
                    $user_id = $cs_examine[$i]['submit_user_id'];
                    if ($i == 0) {
                        $dbleader = \app\index\model\Admin::getdepleaderbyuserid($user_id, '总监');
                        if (empty($dbleader)) {
                            return false;
                        }
                        $cs_examine[$i]['examine_user_id'] = $dbleader[0]['user_id'];
                        $cs_examine[$i]['cs_examine_name'] = $dbleader[0]['fullname'];
                    } else if ($i == 1) {
                        $dbleader = \app\index\model\Admin::getdepleaderbyuserid($user_id, '总经理');
                        if (empty($dbleader)) {
                            return false;
                        }
                        $cs_examine[$i]['examine_user_id'] = $dbleader[0]['user_id'];
                        $cs_examine[$i]['cs_examine_name'] = $dbleader[0]['fullname'];
                    } else if ($i == 2) {
                        $dbleader = \app\index\model\Admin::getdepleaderbyuserid($user_id, '财务部');
                        if (empty($dbleader)) {
                            return false;
                        }
                        $cs_examine[$i]['examine_user_id'] = $dbleader[0]['user_id'];
                        $cs_examine[$i]['cs_examine_name'] = $dbleader[0]['fullname'];
                    }
                    \app\index\model\Admin::addcsexamine($cs_examine[$i]);
                }
            } else {
                \app\index\model\Admin::deleterowtableid('custom_info', 'custom_info_id', $custom_info_id);
                \app\index\model\Admin::deleterowtableid('delivery_info', 'delivery_info_id', $delivery_info_id);
                \app\index\model\Admin::deleterowtableid('return_info', 'return_info_id', $return_info_id);
                //\app\index\model\Admin::deleterowtableid('payment_info','payment_info_id',$delivery_info_id);
            }
            return $retsql;
        //} catch (Exception $e) {
           //return $e;
        //}
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