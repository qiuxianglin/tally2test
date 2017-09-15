<?php
/**
 * 用户登录管理
 */

namespace Index\Controller;
use Think\Controller;

class UserController extends  Controller
{
	//用户登录
	public function login()
	{
		layout(false);
		if(I('post.'))
		{
			$staffno = I('post.staffno');
			$res_s = filterString($staffno);
			if($res_s == false)
			{
				$this->error('用户名不存在');
			}
			//$staffno = str_replace("'", "", $staffno);
			$pwd = I('post.pwd');
			$user = new \Common\Model\UserModel();
			$res = $user->login($staffno, $pwd);
			if($res['code'] == '0')
			{
				$_SESSION ['uid'] = $res['uid'];
				$_SESSION ['u_group_id'] = $res['group_id'];
				$this->success('登录成功',U('Index/index'));
			}else{
				$this->error($res['msg']);
			}
		}else{
			$this->display();
		}
	}
	
	//退出登录
	public function loginout()
	{
		$_SESSION ['uid'] = null;
		$_SESSION ['u_group_id'] = null;
		$this->redirect ( 'login' );
	}
	
	
	//个人信息
	public function personal()
	{
		if($_SESSION['uid'])
		{
			$uid = $_SESSION['uid'];
			$user = new \Common\Model\UserModel();
			//用户信息
			$userMsg=$user->getUserDetail($uid);
			$this->assign('userMsg',$userMsg);
			$this->display();
		}else {
			layout(false);
			$this->error ( '对不起，您尚未登录，请先登录', U ( 'login' ) );
		}
	}
	
	//修改密码
	public function changepwd()
	{
		layout(false);
		if(I('post.')){
			$user = new \Common\Model\UserModel();
			$oldpwd = I('post.oldpwd');
			$newpwd = I('post.newpwd');
			$againpwd = I('post.againpwd');
			$uid = $_SESSION['uid'];
			//判断并进行修改密码
			$res = $user->changePwd($uid, $oldpwd, $newpwd, $againpwd);
			if($res['code'] == '0')
			{
				echo '<script>alert("修改密码成功!");top.location.reload(true);window.close();</script>';
			}else{
				$this->error($res['msg']);
			}
		}else{
		    $this->display();
		}
	}
	
}