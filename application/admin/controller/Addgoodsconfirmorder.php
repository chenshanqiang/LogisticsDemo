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
        $post_data = json_decode($_POST['json']);
        $order_goods_cs_info = $post_data->order_goods_cs_info;
        $ofg_info = $post_data->ofg_info;
        $fee_info = $post_data->fee_info;
        $order_goods_cs_undeliver_goods_info = $post_data->order_goods_cs_undeliver_goods_info;
        $ogcugi_length = count($order_goods_cs_undeliver_goods_info);

        $retofginfo = \app\index\model\Admin::addofginfo($ofg_info);
        if (empty($retofginfo)){
            return "错误  52";
        }
        $retfeeinfo = \app\index\model\Admin::addfeeinfo($fee_info);
        if (empty($retfeeinfo)){
            return "错误  57";
        }
        $ofg_info_id = \app\index\model\Admin::getmaxtableidretid('ofg_info', 'ofg_info_id');
        $fee_info_id = \app\index\model\Admin::getmaxtableidretid('fee_info', 'fee_info_id');
        $order_goods_cs_info->ofg_info_id = $ofg_info_id;
        $order_goods_cs_info->fee_info_id = $fee_info_id;
        $cs_info_id = \app\index\model\Admin::getcsinfomaxid('order_goods_cs_info','cs_id');
        $order_goods_cs_info->cs_id = $cs_info_id;

        for ($i = 0; $i < $ogcugi_length; $i++){
            $order_goods_cs_undeliver_goods_info->$i->cs_id = $cs_info_id;
            $retogcugi = \app\index\model\Admin::addogcugi($order_goods_cs_undeliver_goods_info[$i]);
            if (empty($retogcugi)){
                return "错误  70";
            }
        }
        //还有cs_belong
        $retcsinfo = \app\index\model\Admin::addordergoodscsinfo($order_goods_cs_info);

        if (empty($retcsinfo)){
            //删除相关添加的表  未完
            \app\index\model\Admin::deleterowtableid('ofg_info', 'ofg_info_id', $ofg_info_id);
            \app\index\model\Admin::deleterowtableid('fee_info', 'fee_info_id', $fee_info_id);
            //\app\index\model\Admin::deleterowtableid('ofg_iorder_goods_cs_undeliver_goods_infonfo', 'ogcugi_id', $ogcugi_id);

            return "错误  84";
        }
        return true;
    }
}