<?php
/**
 * 基础类
 * 集装箱-箱主信息维护类
 */
namespace Common\Model;
use Think\Model;

class ContainerMasterModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('ctn_master_code','require','箱主代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('ctn_master_code','preg_match_chinese','箱主代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('ctn_master_code','1,10','箱主代码不超过10个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过10个字符
			array('ctn_master','require','箱主名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('ctn_master','1,20','箱主名称不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
	);
	
	/**
	 * 获取箱主列表
	 * @return array
	 */
	public function getContainerMasterList()
	{
		$containerMasterList=$this->select();
		return $containerMasterList;
	}
	
	/**
	 * 获取箱主信息
	 * @param int $id 箱主ID
	 * @return array
	 */
	public function getContainerMasterMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		return $msg;
	}
}
?>