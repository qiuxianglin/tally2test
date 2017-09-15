<?php
/**
 * 基础类
 * 用户组管理类
 */
namespace Common\Model;
use Think\Model;

class UserGroupModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('title','require','用户组名不能为空',self::EXISTS_VALIDATE),//存在即验证,不能为空
			array('title','1,20','用户组名长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//值不为空即验证，长度不能超过20个字符
			array('status',array('1','0'),'请选择是否开启',self::VALUE_VALIDATE,'in'),//存在即验证,只能是1是 0否
	);
	
	/**
	 * 获取用户组列表
	 * @param int $status 用户组状态：默认空，查找全部;1 正常 0 冻结
	 * @return array
	 */
	public function getUserGroupList($status='')
	{
		if($status!=='')
		{
			$where=array(
					'status'=>$status
			);
		}else {
			$where='1';
		}
		$userGroupList=$this->field('id,title,status')->where($where)->select();
		return $userGroupList;
	}
	
	/**
	 * 获取用户组详情
	 * @param int $id 用户组ID
	 * @return array 一条用户组详情记录
	 */
	public function getUserGroupMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		return $msg;
	}
}
?>