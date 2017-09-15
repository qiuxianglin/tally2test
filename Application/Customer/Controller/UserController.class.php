<?php

/**
 *用户管理
 */
namespace Customer\Controller;
use Think\Controller;

class UserController extends Controller{
	//客户登陆
	public function login()
	{
		layout(false);
		if(I('post.'))
		{
			$customer_code = I('post.customer_code');
			$res_s = filterString($customer_code);
			if($res_s == false)
			{
				$this->error('用户名不存在');
			}
			$customer_pwd = I('post.customer_pwd');
			$customer = new \Common\Model\CustomerModel();
			$res = $customer->login($customer_code, $customer_pwd);
			if($res['code'] == '0')
			{
				$cus = $customer->field('customer_code')->where('id='.$res['id'])->find();
				$_SESSION ['id'] = $res['id'];
				$_SESSION ['customer_code'] = $cus['customer_code'];
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
		$_SESSION ['id'] = null;
		$this->redirect ('login');
	}
	
	//修改密码
	public function changepwd()
	{
		layout(false);
		if(I('post.')){
			$customer = new \Common\Model\CustomerModel();
			$oldpwd = I('post.oldpwd');
			$newpwd = I('post.newpwd');
			$againpwd = I('post.againpwd');
			$id = $_SESSION['id'];
			//判断并进行修改密码
			$res = $customer->changePwd($id, $oldpwd, $newpwd, $againpwd);
			if($res['code'] == '0')
			{
				$_SESSION ['id'] = null;
				echo '<script>alert("修改密码成功!");top.location.reload(true);window.close();</script>';
			}else{
				$this->error($res['msg']);
			}
		}else{
		    $this->display();
		}
	}
	
	//客户信息
	public function personal()
	{
		if($_SESSION['id'])
		{
			$id = $_SESSION['id'];
			$customer = new \Common\Model\CustomerModel();
			//用户信息
			$customerMsg=$customer->getCustomerMsg($id);
			$this->assign('customerMsg',$customerMsg);
			$this->display();
		}else {
			layout(false);
			$this->error ( '对不起，您尚未登录，请先登录', U ( 'login' ) );
		}
	}
	
	//修改客户信息
	public function changepersonal(){
	layout(false);
		if(I('post.')){
			$customer = new \Common\Model\CustomerModel();
			$linkman = I('post.linkman');
			$telephone = I('post.telephone');
			$date = array(
					'linkman'=>$linkman,
					'telephone'=>$telephone
						);
			$id = $_SESSION['id'];
			//判断并进行修改密码
			$res = $customer->where('id='.$id)->save($date);
			if($res != false)
			{
				echo '<script>alert("修改信息成功!");top.location.reload(true);window.close();</script>';
			}else{
				$this->error('修改信息失败!');
			}
		}else{
			$id = $_SESSION['id'];
			$customer = new \Common\Model\CustomerModel();
			//用户信息
			$customerMsg=$customer->getCustomerMsg($id);
			$this->assign('customerMsg',$customerMsg);
			
		    $this->display();
		}
	}
}