<?php
namespace Customer\Controller;
use Think\Controller;
class CommonController extends Controller {
	public function __construct(){
		parent::__construct();
		session_start();
		if(empty($_SESSION['id'])){
			layout(false);
			$this->error('对不起，您尚未登录，请先登录',U('User/login'));
		}
	}
}