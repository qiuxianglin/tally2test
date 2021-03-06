<?php
/**
 * 起泊装箱业务类
 * 预报计划-配货管理
 */
namespace Common\Model;
use Think\Model;

class QbzxPlanCargoModel extends Model
{

	//验证规则
	protected $_validate = array(
		array('plan_id','require','预报计划不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('plan_id','is_positive_int','预报计划不存在',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
		array('billno','require','提单号不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('billno','1,20','提单号长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过20个字符
		array('cargo_name','1,30','货物名长度不能超过30个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过30个字符
		array('number','is_natural_num','货物件数必须为自然数',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为自然数
		array('pack','1,20','包装长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('mark','1,20','标志长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('total_weight','1,20','总重量长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('total_weight','is_natural_num','总重量必须为自然数',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为自然数
		array('dangerlevel','1,20','危险品等级长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('ship_id','1,200','船长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('location_id','1,200','作业地点长度不能超过20个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
	);
	/**
	 * 获取预报计划配货列表
	 * @param int $plan_id:预报计划ID
	 * @return array|boolean
	 */
	public function getCargoList($plan_id)
	{
		$list=$this->where("plan_id=$plan_id")->select();
		if($list!==false)
		{
			return $list;
		}else {
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
		$msg=$this->where("id=$id")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}
}
?>