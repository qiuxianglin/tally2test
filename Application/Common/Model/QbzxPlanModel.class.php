<?php
/**
 * 起泊装箱业务类
 * 预报计划管理
 */
namespace Common\Model;
use Think\Model;

class QbzxPlanModel extends Model
{
	
	//验证规则
	protected  $_validate = array(
		array('entrustno','require','委托编号不能为空',self::EXISTS_VALIDATE),	//存在即验证 不能为空
		array('entrustno','preg_match_chinese','委托编号不能为中文',self::EXISTS_VALIDATE,'function'),//存在即验证 不能为中文
		array('entrustno','1,20','委托编号长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过20个字符
		array('entrust_company','require','委托公司不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('entrust_company','is_positive_int','委托公司不合法',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为自然数
		array('location_id','require','作业场地不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('location_id','is_positive_int','作业地点不合法',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为自然数
		array('ship_id','require','船舶不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('ship_id','is_positive_int','船舶名称不合法',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为自然数
		array('voyage','require','航次不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('voyage','1,20','航次名称不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过20个字符
		array('total_ctn','is_positive_int','总箱数必须为正整数',self::VALUE_VALIDATE,'function'),//存在即验证 必须为正整数
		array('total_ctn','1,11','总箱数长度不能超过11个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过20个字符
		array('total_ticket','require','总票数不能为空',self::EXISTS_VALIDATE),//存在即验证 不能为空
		array('total_ticket','is_positive_int','总票数必须为正整数',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
		array('total_ticket','1,11','总票数长度不能超过11个字符',self::EXISTS_VALIDATE,'length'),//存在即验证 长度不能超过20个字符
		array('total_package','is_positive_int','总件数必须为正整数',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为正整数
		array('total_package','1,11','总件数长度不能超过11个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('total_weight','1,11','总重量长度不能超过11个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过20个字符
		array('total_weight','is_positive_int','总重量必须为正整数',self::VALUE_VALIDATE,'function'),//值不为空即验证
		array('cargo_agent_id','require','货代不能为空',self::EXISTS_VALIDATE),//存在即验证
	    array('cargo_agent_id','is_positive_int','货代不合法',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
	    array('packing_require','1,1000','装箱要求长度不能超过1000个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证 长度不能超过1000个字符
	    array('entrust_time','is_date','委托时间必须为日期格式',self::VALUE_VALIDATE,'function'),//值不为空即验证 必须为日期格式
	    array('port_id','require','目的港不能为空',self::EXISTS_VALIDATE),//存在即验证 
		array('port_id','is_positive_int','目的港不合法',self::EXISTS_VALIDATE,'function'),//存在即验证 必须为正整数
	);
	/**
	 * 获取预报计划详情
	 * @param int $id:预报计划ID
	 * @return array|boolean
	 */
	public function getPlanMsg($id)
	{
		$sql="select p.*,s.ship_name,l.location_name,c.customer_name as customer,ca.name as cargo_agent_name from __PREFIX__qbzx_plan p,__PREFIX__ship s,__PREFIX__location l,__PREFIX__customer c,__PREFIX__cargo_agent ca where p.id='$id' and p.ship_id=s.id and p.location_id=l.id and p.entrust_company=c.id and p.cargo_agent_id=ca.id";
		$res=M()->query($sql);
		if($res!==false)
		{
			// 目的港
			$port = new \Common\Model\PortModel ();
			$port_id = $res[0]['port_id'];
			$name = $port->where("id='$port_id'")->find();
			$res[0]['poname'] = $name['name'];
			$msg=$res[0];
			return $msg;
		}else {
			return false;
		}
	}
}
?>