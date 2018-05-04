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
    	return $this->fetch();
    }
}
