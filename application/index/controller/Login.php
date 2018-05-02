<?php
namespace app\index\controller;
use think\Controller;

class Login extends Controller
{
	/*登录页渲染方法*/
    public function login(){
    	return $this->fetch();
    }

    /*登录验证*/
    public function  loginconfirm(){
    	$fullname = $_POST['fullname'];
    	$password = $_POST['password'];

    	$ret = \app\index\model\Admin::login($fullname,$password);
    	return $ret;
    }
}
