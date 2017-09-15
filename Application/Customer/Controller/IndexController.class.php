<?php

namespace Customer\Controller;
use Think\Controller;

class IndexController extends  CommonController
{
	public function index(){
		if($_SESSION['id']){
			$id = $_SESSION['id'];
			$customer = new \Common\Model\CustomerModel();
			//客户信息
			$customerMsg=$customer->getCustomerMsg($id);
			$this->assign('customerMsg',$customerMsg);
			$this->display();
		}else{
			layout(false);
			$this->error('对不起，您尚未登录，请先登录',U('User/login'));
		}
	}
}