<?php
namespace app\index\controller;
use think\Controller;

class Adminindex extends Controller
{	
	/*首页渲染方法*/
    public function index(){
    	return $this->fetch();
    }
}


