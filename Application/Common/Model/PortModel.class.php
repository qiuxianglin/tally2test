<?php
/**
 * 基础类
 * 港口信息维护类
 */
namespace Common\Model;
use Think\Model;

class PortModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('code','require','港口代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('code','preg_match_chinese','港口代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('code','1,20','港口代码不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
			array('name','require','港口中文名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('name','1,50','港口中文名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过50个字符
			array('name_en','1,50','港口英文名称不超过50个字符！',self::VALUE_VALIDATE,'length'),  //存在即验证，长度不超过50个字符
	);
	
	/**
	 * 获取港口列表
	 * @return array
	 */
	public function getPortList()
	{
		$portList=$this->select();
		return $portList;
	}
	
	/**
	 * 获取港口信息
	 * @param int $id:港口ID
	 * @return array
	 */
	public function getPortMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		return $msg;
	}
}
?>