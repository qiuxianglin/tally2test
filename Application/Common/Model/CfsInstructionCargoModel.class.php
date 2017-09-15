<?php
/**
 * CFS装箱业务类
 * 配货管理
 */
namespace Common\Model;
use Think\Model;

class CfsInstructionCargoModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('blno','require','提单号不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('blno','1,35','提单号长度不能超过35个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过35个字符
		array('crgno','1,35','运输单号长度不能超过35个字符',self::VALUE_VALIDATE,'length'),//存在即验证 长度不能超过35个字符
		array('name','require','货名不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('name','1,50','货名长度不能超过50个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过50个字符
		array('number','require','货物件数不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('number','is_positive_int','货物件数必须为正整数',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
		array('package','require','包装不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('package','1,50','包装长度不能超过50个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过50个字符
		array('mark','require','标志不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('mark','1,50','标志长度不能超过50个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过50个字符
		array('totalweight','currency','总重量必须为数字',self::VALUE_VALIDATE),//值不为空即验证 
		array('dangerlevel','1,20','危险品等级长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('totalvolume','currency','总体积必须为数字',self::VALUE_VALIDATE),//值不为空即验证
		array('po','1,20','po号长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('remark','1,200','备注长度不能超过200个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过200个字符
		array('instruction_id','require','指令不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('instruction_id','is_positive_int','指令不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
	);
	/**
	 * 获取作业指令配货列表
	 * @param int $instruction_id:指令ID
	 * @return array|boolean
	 */
	public function getCargoList($instruction_id)
	{	
		$list = $this->where("instruction_id='$instruction_id'")->select();
		if($list !== false)
		{
			return $list;
		}else{
			return false;
		}
	}
	/**
	 * 获取配货详情
	 * @param int $id:配货ID
	 * @return array|boolean
	 */
	public function getCargoMsg($id)
	{
		$msg = $this->where("id='$id'")->find();
		if($msg !== false)
		{
			return $msg;
		}else{
			return false;
		}
	}
}