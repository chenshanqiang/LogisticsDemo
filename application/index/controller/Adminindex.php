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

        /*用户管理权限*/
        $usermanagepower = 0x01;
        $this->assign('usermanagepower',$usermanagepower);

        /*新增订货确认单权限*/
        $addgoodsorderpower = 0x01;
        $this->assign('addgoodsorderpower',$addgoodsorderpower);

        /*新增订单权限*/
        $addorderpower = 0x01;
        $this->assign('addorderpower',$addorderpower);

        $addreplaceorderpower = 0x01;
        $this->assign('addreplaceorderpower',$addreplaceorderpower);

        $addborroworderpower = 0x02;
        $this->assign('addborroworderpower',$addborroworderpower);

        $addreturnorderpower = 0x03;
        $this->assign('addreturnorderpower',$addreturnorderpower);

        $addrepairorderpower = 0x04;
        $this->assign('addrepairorderpower',$addrepairorderpower);

        $addpartsorderpower = 0x05;
        $this->assign('addpartsorderpower',$addpartsorderpower);

        $addalternativeorderpower = 0x06;
        $this->assign('addalternativeorderpower',$addalternativeorderpower);

        /*物流单号输入权限*/
        $addlogisticorder = 0x01;
        $this->assign('addlogisticorder',$addlogisticorder);

        /*订单审批权限权限*/
        $approvepower = 0x01;
        $this->assign('approvepower',$approvepower);

        $approvereplacepower = 0x01;
        $this->assign('approvereplacepower',$approvereplacepower);

        $approveborrowpower = 0x02;
        $this->assign('approveborrowpower',$approveborrowpower);

        $approvereturnpower = 0x03;
        $this->assign('approvereturnpower',$approvereturnpower);

        $approverepairpower = 0x04;
        $this->assign('approverepairpower',$approverepairpower);

        $approvepartspower = 0x05;
        $this->assign('approvepartspower',$approvepartspower);

        $approvealternativepower = 0x06;
        $this->assign('approvealternativepower',$approvealternativepower);

        /*确认单查询权限*/
        $querypower = 0x01;
        $this->assign('querypower',$querypower);

        $querygoodspower = 0x01;
        $this->assign('querygoodspower',$querygoodspower);

        $queryreplacepower = 0x02;
        $this->assign('queryreplacepower',$queryreplacepower);

        $queryborrowpower = 0x03;
        $this->assign('queryborrowpower',$queryborrowpower);

        $queryreturnpower = 0x04;
        $this->assign('queryreturnpower',$queryreturnpower);

        $queryrepairpower = 0x05;
        $this->assign('queryrepairpower',$queryrepairpower);

        $querypartspower = 0x06;
        $this->assign('querypartspower',$querypartspower);

        $queryalternativepower = 0x07;
        $this->assign('queryalternativepower',$queryalternativepower);
	   	return $this->fetch();
    }

    /*退出登录*/
    public function logout(){
        $ret = \app\index\model\Admin::logout();
        return $ret;
    }
}


