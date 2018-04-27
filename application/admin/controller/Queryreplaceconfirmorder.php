<?php
namespace app\admin\controller;
use think\Controller;
use think\Input;

class Queryreplaceconfirmorder extends Controller
{
	/*查询订单渲染方法*/
    public function queryreplaceconfirmorder(){
        $organizeInfo = \app\index\model\Admin::getclassinfo('dsp_logistic.organize','organize_id');
        $this->assign('organizelist',$organizeInfo);

        $producttype = \app\index\model\Admin::getclassinfo('dsp_logistic.product_type','type_id');
        $this->assign('productlist',$producttype);

        $productPlace = \app\index\model\Admin::getclassinfo('dsp_logistic.product_place','place_id');
        $this->assign('placelist',$productPlace);

        $productBrand = \app\index\model\Admin::getclassinfo('dsp_logistic.product_brand','brand_id');
        $this->assign('brandlist',$productBrand);

        $uncProduct = \app\index\model\Admin::getclassinfo('dsp_logistic.unc_product','unc_product_id');
        $this->assign('unclist',$uncProduct);
        return $this->fetch();
    }

    public function getexamineorder(){
    	$page = $_GET['page'];
    	$limit = $_GET['limit'];
        $type = 0x01;

    	if(isset($_GET['queryInfo'])){
    		$queryInfo = $_GET['queryInfo'];
    		$tablelist = \app\index\model\Admin::querycsInfomation($type,$page,$limit,$queryInfo);
    	}else{
    		$tablelist = \app\index\model\Admin::querycsInfomation($type,$page,$limit);
    	}
        
    	return $tablelist;
    }
}