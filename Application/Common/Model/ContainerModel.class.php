<?php
/**
 * 基础类
 * 集装箱-箱型信息维护类
 */
namespace Common\Model;
use Think\Model;

class ContainerModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('ctn_type_code','require','箱型代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('ctn_type_code','preg_match_chinese','箱型代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('ctn_type_code','1,20','箱型代码不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
			array('ctn_type','require','箱型不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('ctn_type','1,20','箱型不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
			array('ctn_size','is_natural_num','箱尺寸只能为数字！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证 ，必须为自然数
	);
	
	/**
	 * 获取箱型列表
	 * @return array
	 */
	public function getContainerList()
	{
		$containerList=$this->select();
		return $containerList;
	}
	
	/**
	 * 获取箱型信息
	 * @param int $id 箱型ID
	 * @return array 一条箱型详情记录
	 */
	public function getContainerMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		return $msg;
	}
}
?>