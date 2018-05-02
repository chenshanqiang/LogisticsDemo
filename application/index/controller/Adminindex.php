<?php
namespace app\index\controller;
use think\Controller;

class Adminindex extends Controller
{	
	/*首页渲染方法*/
    public function index(){
    	/*更换确认单*/
    	$this->assign('replaceorder',10);

    	/*借样确认单*/
    	$this->assign('borroworder',5);

    	/*退货确认单*/
    	$this->assign('returnorder',3);

    	/*维修确认单*/
    	$this->assign('repairorder',2);

    	/*配件确认单*/
    	$this->assign('partsorder',2);

    	/*代用确认单*/
    	$this->assign('alternativeorder',1);

        $this->assign('name',2);
	   	return $this->fetch();
    }

    /*退出登录*/
    public function logout(){
        $ret = \app\index\model\Admin::logout();
        return $ret;
    }
}


