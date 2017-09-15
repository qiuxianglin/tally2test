<?php
namespace Index\Controller;
use Index\Common\BaseController;

class IndexController extends BaseController 
{
	/* 首页 */
	public function index()
	{
		if($_SESSION ['uid'])
		{
			$uid = $_SESSION['uid'];
			$user = new \Common\Model\UserModel();
			//用户信息
			$userMsg=$user->getUserDetail($uid);
			$this->assign('userMsg',$userMsg);
			$this->display();
		}else{
			layout(false);
			$this->error ( '对不起，您尚未登录，请先登录', U ( 'User/login' ) );
		}
	}
}