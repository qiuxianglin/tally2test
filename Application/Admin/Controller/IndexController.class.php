<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	layout(false);
        $this->display();
    }
    
    public function loginin()
    {
    	layout(false);
    	if(I('post.adminuser') and I('post.adminpwd'))
    	{
    		$adminuser=I('post.adminuser');
    		$adminpwd=I('post.adminpwd');
    		
    		//记住账号
    		$remember=I('post.remember');
    		if(!empty($remember))
    		{
    			cookie('remember',$remember,3600*24*30);
    			cookie('loginname',$adminuser,3600*24*30);
    			cookie('loginpwd',$adminpwd,3600*24*30);
    		}else {
    			cookie('remember',null);
    			cookie('loginname',null);
    			cookie('loginpwd',null);
    		}
    		
    		$auth=I('post.auth');
    		$verify = new \Think\Verify();
    		$res=$verify->check($auth, '');
    		if($res==false)
    		{
    			$this->assign('error','验证码不正确！');
    			$this->display('index');
    			exit();
    		}
    		$admin=D('admin');
    		$res=$admin->where("adminname='$adminuser'")->find();
    		if($res)
    		{
    			$status=$res['status'];
    			if($status==0)
    			{
    				$this->assign('error','该用户已被禁用！');
    				$this->display('index');
    				exit();
    			}else {
    				$password=$res['password'];
    				//MD5加密
    				$pstr=$adminpwd.'9'.substr($adminpwd,0,3);
    				$pwd=md5($pstr);
    				if($password!=$pwd)
    				{
    					$this->assign('error','用户名或密码错误！');
    					$this->display('index');
    					exit();
    				}else {
    					//判断管理员组是否被禁用
    					$group_id=$res['group_id'];
    					$res_g=D('AdminGroup')->where("id=$group_id")->field('status')->find();
    					if($res_g['status']=='1')
    					{
    						//更新登录状态
    						$last_login_time=date('Y-m-d H:i:s');
    						//php获取ip的算法
    						$ip = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
    						$ip = ($ip) ? $ip : $_SERVER["REMOTE_ADDR"];
    						$login_num=$res['login_num']+1;
    						$data=array(
    								'last_login_time'=>$last_login_time,
    								'last_login_ip'=>$ip,
    								'login_num'=>$login_num
    						);
    						$res2=$admin->where("adminname='$adminuser'")->save($data);
    						if($res2)
    						{
    							//保存用户SESSION
    							$_SESSION['admin_id']=$res['uid'];
    							$_SESSION['group_id']=$res['group_id'];
    							//跳转页面
    							$this->redirect('System/index');
    						}else {
    							$this->assign('error','登录失败！');
    							$this->display('index');
    						}
    					}else {
    						$this->assign('error','您所在的管理员组已被禁用！');
    						$this->display('index');
    						exit();
    					}
    				}
    			}
    		}else {
    			$this->assign('error','用户不存在！');
    			$this->display('index');
    		}
    	}else {
    		$this->assign('error','账号、密码不能为空！');
    		$this->display('index');
    	}
    }
    
    //退出登录
    public function loginout()
    {
    	$_SESSION['admin_id']=null;
    	$_SESSION['group_id']=null;
    	//跳转页面
    	$this->redirect('Index/index');
    }
    
    public function verify()
    {
    	ob_clean();
    	$config =	array(
    			'expire'    =>  1800,            // 验证码过期时间（s）
    			'useImgBg'  =>  false,           // 使用背景图片
    			'fontSize'  =>  10,              // 验证码字体大小(px)
    			'useCurve'  =>  false,            // 是否画混淆曲线
    			'useNoise'  =>  false,            // 是否添加杂点
    			'imageH'    =>  30,               // 验证码图片高度
    			'imageW'    =>  80,               // 验证码图片宽度
    			'length'    =>  4,               // 验证码位数
    			'fontttf'   =>  '5.ttf',              // 验证码字体，不设置随机获取
    			'bg'        =>  array(243, 251, 254),  // 背景颜色
    	);
    	$verify=new \Think\Verify($config);
    	/**
    	 * 输出验证码并把验证码的值保存的session中
    	* */
    	$verify->entry();
    }
}