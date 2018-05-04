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

        /*获取权限设置*/
        $userinfo = \app\index\model\Admin::getsessioninfo();
        $role_id = intval($userinfo["role_id"]);
        $role_info = \app\index\model\Admin::queryroleinfo($role_id);

        $exportgoodspower = ($role_info[0]['order_goods_permission'])&0x10;
        $exportreplacepower = ($role_info[0]['replace_permission'])&0x10;
        $exportborrowpower = ($role_info[0]['borrow_sample_permission'])&0x10;
        $exportreturnpower = ($role_info[0]['return_goods_permission'])&0x10;
        $exportpartspower = ($role_info[0]['maintain_permission'])&0x10;
        $exportrepairpower = ($role_info[0]['fixing_permission'])&0x10;
        $exportalternativepower = ($role_info[0]['substitute_permission'])&0x10;

        $this->assign('exportgoodspower',$exportgoodspower);
        $this->assign('exportreplacepower',$exportreplacepower);
        $this->assign('exportborrowpower',$exportborrowpower);
        $this->assign('exportreturnpower',$exportreturnpower);
        $this->assign('exportpartspower',$exportpartspower);
        $this->assign('exportrepairpower',$exportrepairpower);
        $this->assign('exportalternativepower',$exportalternativepower);
    	return $this->fetch();
    }
}
