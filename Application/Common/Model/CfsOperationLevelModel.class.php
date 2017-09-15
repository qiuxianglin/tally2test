<?php
/**
 * CFS装箱业务类
 * 作业录关管理类
 */
namespace Common\Model;
use Think\Model;

class CfsOperationLevelModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('operation_id','require','作业不能为空',self::EXISTS_VALIDATE), //存在验证，不能为空
			array('operation_id','is_positive_int','作业不存在',self::EXISTS_VALIDATE,'function'), //存在验证，必须为正整数
			array('level_num','require','关数不能为空',self::EXISTS_VALIDATE),//存在验证，不能为空
			array('level_num','is_positive_int','关数必须为正整数',self::EXISTS_VALIDATE,'function'), //存在即验证，必须为正整数
			array('num','require','货物重量不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('num','number','货物重量必须为数字',self::EXISTS_VALIDATE), // 存在即验证，必须为数字
			array('level_img','require','关照片不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('level_img','1,255','关照长度不能超过255个字符',self::EXISTS_VALIDATE,'length'), //值不为空验证，长度不能超过255个字符
			array('damage_num','number','残损数量必须为数字',self::VALUE_VALIDATE),//值不为空即验证，必须为数字
			array('blno','require','提单号不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('blno','1,20','提单号长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过20个字符
			array('operator_id','require','操作人不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('operator_id','is_positive_int','操作人不存在',self::EXISTS_VALIDATE,'function'),//存在即验证，必须为正整数
			array('creatime','require','完成时间不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('creatime','is_datetime','完成时间必须为时间格式',self::EXISTS_VALIDATE,'function'),//存在即验证，必须为时间格式
	);
	/**
	 * 获取关列表
	 * @param int $operation_id:作业ID
	 * @return array|boolean
	 */
	public function getLevelList($operation_id)
	{
		$sql="select l.*,u.user_name,ic.mark,ic.package,ic.port_id from __PREFIX__cfs_operation_level l,__PREFIX__user u,__PREFIX__cfs_instruction_cargo ic where l.operation_id=$operation_id and l.operator_id=u.uid GROUP BY level_num";
		// $sql="select l.*,u.user_name,ic.mark,ic.package,ic.port_id from __PREFIX__cfs_operation_level l,__PREFIX__user u,__PREFIX__cfs_instruction_cargo ic where l.operation_id=$operation_id and l.operator_id=u.uid and ic.blno=l.blno GROUP BY l.operation_id";
		$list=M()->fetchsql()->query($sql);
		if($list!==false)
		{
			$num=count($list);
			$cargo_damage_img=new \Common\Model\CfsCargoDamageImgModel();
			$level_cargo_img = new \Common\Model\CfsLevelCargoImgModel();
			for ($i=0;$i<$num;$i++)
			{
				//根据关ID获取关的货残损照片
				$level_id=$list[$i]['id'];
				$cargo_damage_img_list=$cargo_damage_img->where("level_id=$level_id")->select();
				if($cargo_damage_img_list!==false)
				{
					$list[$i]['cargo_damage_img']=$cargo_damage_img_list;
				}else {
					$list[$i]['cargo_damage_img']='';
				}
				//根据关ID获取关的货wu照片
				$level_cargo_img_list = $level_cargo_img->where("level_id=$level_id")->select();
				if($level_cargo_img_list !== false)
				{
					$list[$i]['level_cargo_img'] = $level_cargo_img_list;
				}else{
					$list[$i]['level_cargo_img'] = '';
				}
				//根据port_id 获取目的港名称
				$port_id = $list[$i]['port_id'];
				$port = new \Common\Model\PortModel();
				$port_name = $port->field('name')->where("id='$port_id'")->find();
				$list[$i]['pname'] = $port_name['name'];
			}
			return $list;
		}else {
			return false;
		}
	}
	

	/**
	 * 根据作业ID计算关数
	 * @param int $operation_id:作业ID
	 * @return number
	 */
	public function sumLevelNum($operation_id)
	{
		$num=$this->where("operation_id='$operation_id'")->count();
		return $num;
	}
	
	/**
	 * 根据作业ID计算货物件数
	 * @param int $operation_id:作业ID
	 * @return number
	 */
	public function sumCargoNum($operation_id)
	{
		$num=$this->where("operation_id=$operation_id")->sum('num');
		return $num;
	}
	
	/**
	 * 根据作业ID计算残损件数
	 * @param int $operation_id:作业ID
	 * @return number
	 */
	public function sumDamageNum($operation_id)
	{
		$num=$this->where("operation_id=$operation_id")->sum('damage_num');
		return $num;
	}
	
}
?>
