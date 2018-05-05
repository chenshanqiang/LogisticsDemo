<?php
namespace app\admin\controller;
use think\Controller;
use think\Input;

class Addgoodsconfirmorder extends Controller
{
	/*新增订单渲染方法*/
    public function addgoodsconfirmorder(){
        $producttype = \app\index\model\Admin::getclassinfo('product_type','product_type_id');
        $brand = \app\index\model\Admin::getclassinfo('product_brand','brand_id');
        $place = \app\index\model\Admin::getclassinfo('product_place','place_id');
        if (!empty($brand)){
            $this->assign('producttypelist',$producttype);
        }
        if (!empty($brand)){
            $this->assign('brandlist',$brand);
        }
        if (!empty($place)){
            $this->assign('placelist',$place);
        }
        $date = date('Ymd');
        $this->assign("date", $date);
        $companytable = \app\index\model\Admin::querydepartmentinfo(0);
        if (!empty($companytable))
            $this->assign("companylist", $companytable);
    	return $this->fetch();
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

    public function addcs(){
        $order_goods_cs_info = $_POST['order_goods_cs_info'];
        $ofg_info = $_POST['ofg_info'];
        $fee_info = $_POST['fee_info'];

        /*
        $retcsinfo = \app\index\model\Admin::addordergoodscsinfo($order_goods_cs_info);
        $retofginfo = \app\index\model\Admin::addofginfo($ofg_info);
        $retfeeinfo = \app\index\model\Admin::addfeeinfo($fee_info);
        */
    }
}