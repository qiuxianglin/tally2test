<?php
/**
 * 基础类
 * 货代信息维护类
 */
namespace Common\Model;
use Think\Model;

class CargoAgentModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('code','require','货代代码不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('code','preg_match_chinese','货代代码不能使用中文！',self::EXISTS_VALIDATE,'function'),  //存在即验证，不准使用中文
			array('code','1,20','货代代码不超过20个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过20个字符
			array('name','require','货代中文名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('name','1,50','货代中文名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过50个字符
			array('name_en','require','货代英文名称不能为空！',self::EXISTS_VALIDATE),          //存在即验证，不能为空
			array('name_en','1,50','货代英文名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在即验证，长度不超过50个字符
			array('contacter','1,20','联系人不超过20个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过20个字符
			array('telephone','1,50','联系电话不超过50个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过50个字符
			array('remark','1,200','备注不超过200个字符！',self::VALUE_VALIDATE,'length'),  //值不为空的时候验证，长度不超过200个字符
	);
	
	/**
	 * 获取货代列表
	 * @param string $order:排序规则 默认asc
	 * @return array|boolean
	 */
	public function getCargoAgentList($order='asc')
	{
		$list=$this->order("id $order")->select();
		if ($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取货代信息
	 * @param int $id:货代ID
	 * @return array
	 */
	public function getCargoAgentMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		if ($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
}
?>