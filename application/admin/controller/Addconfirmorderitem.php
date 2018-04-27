<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/04/27
 * Time: 11:38
 */

namespace app\admin\controller;
use think\Controller;

class Addconfirmorderitem extends Controller
{
    public function addconfirmorderitem()
    {
        $producttype = \app\index\model\Admin::getclassinfo('product_type','type_id');
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
        return $this->fetch();
    }

    public function serachmodelinfo()
    {
        $sereachText = $_POST['serrchText'];
        $productType = $_POST['type'];
        $brand = $_POST['brand'];
        return $sereachText.'  '.$productType.'  '.$brand;
    }
}