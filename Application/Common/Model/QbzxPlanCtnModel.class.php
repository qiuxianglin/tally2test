<?php
/**
 * 起泊装箱业务类
 * 预报计划-配箱管理
 */
namespace Common\Model;
use Think\Model;

class QbzxPlanCtnModel extends Model
{
	
	//验证规则
	protected $_validate = array(
			array('plan_id','is_positive_int','预报计划不合法',self::EXISTS_VALIDATE,'function'), //存在即验证，必须为正整数
			array('ctn_type_code','require','箱型尺寸不能为空',self::EXISTS_VALIDATE),//存在即验证,不能为空
			array('ctn_type_code','preg_match_chinese','箱型尺寸不能用中文',self::EXISTS_VALIDATE,'function'),//存在即验证，不能使用中文
			array('ctn_type_code','1,10','箱型尺寸长度不能超过10个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 ，不能超过0个字符
			array('quantity','require','箱子个数必须为正整数',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('quantity','is_positive_int','箱子个数必须为正整数',self::VALUE_VALIDATE,'function'),//存在即验证 必须为正整数
			array('quantity','1,9','箱子个数不能超过9个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 ，不能超过0个字符
			array('ctn_master','is_positive_int','箱主不存在',self::VALUE_VALIDATE,'function'),//值不为空即验证，必须为正整数
			array('flflag',array('F','L'),'请选择是否整拼',self::VALUE_VALIDATE,'in'),//值不为空即验证,是Y否N
	);
	/**
	 * 获取预报计划配箱列表
	 * @param int $plan_id:预报计划ID
	 * @return array|boolean
	 */
	public function getContainerList($plan_id)
	{
// 		$sql="select c.*,m.ctn_master from __PREFIX__qbzx_plan_ctn c,__PREFIX__container_master m where c.plan_id=$plan_id and c.ctn_master=m.id order by c.id asc";
// 		$list=M()->query($sql);
		$list = M('qbzx_plan_ctn')->field("c.*,m.ctn_master")->alias('c')->join("left join tally_container_master m on c.ctn_master=m.id")->where("c.plan_id='$plan_id'")->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取配箱详情
	 * @param int $id:配箱ID
	 * @return array|boolean
	 */
	public function getContainerMsg($id)
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