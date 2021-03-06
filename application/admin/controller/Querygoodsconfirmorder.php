<?php
namespace app\admin\controller;
use think\Controller;
use think\Input;

class Querygoodsconfirmorder extends Controller
{
	/*查询订单渲染方法*/
    public function querygoodsconfirmorder(){
        $organizeInfo = \app\index\model\Admin::getclassinfo('dsp_logistic.organize','organize_id');
        $this->assign('organizelist',$organizeInfo);

        $producttype = \app\index\model\Admin::getclassinfo('dsp_logistic.product_type','product_type_id');
        $this->assign('productlist',$producttype);

        $productPlace = \app\index\model\Admin::getclassinfo('dsp_logistic.product_place','place_id');
        $this->assign('placelist',$productPlace);

        $productBrand = \app\index\model\Admin::getclassinfo('dsp_logistic.product_brand','brand_id');
        $this->assign('brandlist',$productBrand);

        $uncProduct = \app\index\model\Admin::getclassinfo('dsp_logistic.unc_product','unc_product_id');
        $this->assign('unclist',$uncProduct);

        /*获取权限设置*/
        $userinfo = \app\index\model\Admin::getsessioninfo();
        $role_id = intval($userinfo["role_id"]);
        $role_info = \app\index\model\Admin::queryroleinfo($role_id);

        $exportgoodspower = ($role_info[0]['order_goods_permission'])&0x10;
        $exportreplacepower = 0;
        $exportborrowpower = 0;
        $exportreturnpower = 0;
        $exportpartspower = 0;
        $exportrepairpower = 0;
        $exportalternativepower = 0;
        $editgoodspower = ($role_info[0]['order_goods_permission'])&0x02;
        $deletegoodspower = ($role_info[0]['order_goods_permission'])&0x04;

        $this->assign('editgoodspower',$editgoodspower);
        $this->assign('deletegoodspower',$deletegoodspower);
        $this->assign('exportgoodspower',$exportgoodspower);
        $this->assign('exportreplacepower',$exportreplacepower);
        $this->assign('exportborrowpower',$exportborrowpower);
        $this->assign('exportreturnpower',$exportreturnpower);
        $this->assign('exportpartspower',$exportpartspower);
        $this->assign('exportrepairpower',$exportrepairpower);
        $this->assign('exportalternativepower',$exportalternativepower);
        return $this->fetch();
    }

    public function getexamineorder(){
    	$page = $_GET['page'];
    	$limit = $_GET['limit'];

    	if(isset($_GET['queryInfo'])){
    		$queryInfo = $_GET['queryInfo'];
    		$tablelist = \app\index\model\Admin::querygoodsorderinfo($page,$limit,$queryInfo);
    	}else{
    		$tablelist = \app\index\model\Admin::querygoodsorderinfo($page,$limit);
    	}
        
    	return $tablelist;
    }
}