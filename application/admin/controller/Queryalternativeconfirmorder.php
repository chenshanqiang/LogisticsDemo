<?php
namespace app\admin\controller;
use think\Controller;
use think\Input;

class Queryalternativeconfirmorder extends Controller
{
	/*查询订单渲染方法*/
    public function queryalternativeconfirmorder(){
        
        $producttype = \app\index\model\Admin::getclassinfo('dsp_logistic.product_type ','product_type_id ');
        $this->assign('productlist',$producttype);

        $productPlace = \app\index\model\Admin::getclassinfo('dsp_logistic.product_place','place_id');
        $this->assign('placelist',$productPlace);

        $productBrand = \app\index\model\Admin::getclassinfo('dsp_logistic.product_brand','brand_id');
        $this->assign('brandlist',$productBrand);

        $uncProduct = \app\index\model\Admin::getclassinfo('dsp_logistic.unc_product','unc_product_id');
        $this->assign('unclist',$uncProduct);
        return $this->fetch();
    }
    protected $isSales = false; //是否是销售人员（总经理、总监、经理）
    protected $organizename = ""; //总经理name
    protected $departmentname = "";//总监名
    protected $areamanager = "";//经理名

    public function getexamineorder(){
    	$page = $_GET['page'];
    	$limit = $_GET['limit'];
        $type = 0x06;
        if(!$this->isSales)
        {
            if(isset($_GET['queryInfo'])){
                $queryInfo = $_GET['queryInfo'];
                $tablelist = \app\index\model\Admin::querycsInfomation($type,$page,$limit,$queryInfo);
            }else{
                $tablelist = \app\index\model\Admin::querycsInfomation($type,$page,$limit);
            }
        }
        else
        {
            if(isset($_GET['queryInfo'])){
                $queryInfo = $_GET['queryInfo'];
                $tablelist = \app\index\model\Admin::querycsinfobysales($this->organizename,$this->departmentname,$this->areamanager,$type,$page,$limit,$queryInfo);
            }else{
                $tablelist = \app\index\model\Admin::querycsinfobysales($this->organizename,$this->departmentname,$this->areamanager,$type,$page,$limit);
            }
        }

    	return $tablelist;
    }
}