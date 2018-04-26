<?php
namespace app\admin\controller;
use think\Controller;

class Publictemplate extends Controller
{	
	/*首页渲染方法*/
    public function approveserachcondtion(){
    	return $this->fetch();
    }

    public function querysearchcondition(){
    	$organizeInfo = \app\index\model\Admin::getclassinfo('dsp_logistic.organize','organize_id');
    	$this->assign('organizelist',$organizeInfo);

    	$producttype = \app\index\model\Admin::getclassinfo('dsp_logistic.product_type','type_id');
    	$this->assign('productlist',$producttype);

    	$productPlace = \app\index\model\Admin::getclassinfo('dsp_logistic.product_place','place_id');
    	$this->assign('placelist',$productPlace);

    	$productBrand = \app\index\model\Admin::getclassinfo('dsp_logistic.product_brand','brand_id');
    	$this->assign('brandlist',$productBrand);
    	return $this->fetch();
    }
}
