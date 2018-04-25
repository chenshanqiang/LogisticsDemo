<?php
namespace app\index\controller;
use think\Controller;

class Login extends Controller
{
	/*登录页渲染方法*/
    public function login(){
    	return $this->fetch();
    }
}
