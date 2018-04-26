<?php
namespace app\index\controller;
use think\Controller;

class Adminindex extends Controller
{	
	/*首页渲染方法*/
    public function index(){

        //麻烦传个 字段loginuserid 的值
    	return $this->fetch();
    }
}


