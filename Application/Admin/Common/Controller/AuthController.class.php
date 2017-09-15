<?php
/*
 * @thinkphp3.2.2  auth认证   php5.3以上
 * @如果需要公共控制器，就不要继承AuthController，直接继承Controller
 */
namespace Admin\Common\Controller;
use Think\Controller;
use Think\Auth;
use Think\Model;

//权限认证
class AuthController extends Controller {
	protected function _initialize(){
		//session不存在时，不允许直接访问
		if(!$_SESSION['admin_id'])
		{
			layout(false);
			$this->error('还没有登录，正在跳转到登录页',U('Index/index'));
		}
		$admin_id=$_SESSION['admin_id'];
		$adminmsg=D('Admin')->getAdminMsg($admin_id);
		//保存用户SESSION
		$_SESSION['adminname']=$adminmsg['adminname'];
		$_SESSION['group_title']=$adminmsg['group_title'];	
		
		//session存在时，不需要验证的权限
		$not_check = array(
				'Admin/changepwd','System/index','System/cleancache',//修改密码、系统首页、清理缓存
				'Customer/down','User/down',//下载批量导入客户示例文件
		);
		
		//当前操作的请求                 模块名/方法名
		if(in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $not_check))
		{
			return true;
		}
		
		$auth = new Auth();
		if(!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME,$_SESSION['admin_id']) and $_SESSION['group_id']!='1')
		{
			layout(false);
			$this->error ( '没有权限' );
		}
	}
}