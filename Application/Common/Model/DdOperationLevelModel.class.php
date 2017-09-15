<?php
/**
 * 门到门拆箱业务类
 * 作业录关管理类
 */
namespace Common\Model;
use Think\Model;

class DdOperationLevelModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('operation_id','require','请选择所属的箱子',self::EXISTS_VALIDATE), //存在验证，不能为空		
		array('operation_id','is_positive_int','请选择所属的箱子',self::EXISTS_VALIDATE,'function'), //存在验证，必须为正整数
		array('level_num','require','不能为空',self::EXISTS_VALIDATE),//存在验证，不能为空
		array('level_num','is_positive_int','关序号必须为正整数',self::EXISTS_VALIDATE,'function'), //存在即验证，必须为正整数
		array('num','require','货物件数不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('num','number','货物件数必须为数字',self::EXISTS_VALIDATE), // 存在即验证，必须为数字
		//array('damage_num','number','残损件数必须为数字',self::VALUE_VALIDATE),//值不为空即验证，必须为数字	
		array('operator_id','require','理货员不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('operator_id','is_positive_int','理货员不存在',self::EXISTS_VALIDATE,'function'),//存在即验证，必须为正整数
		array('creatime','require','操作时间不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('creatime','is_datetime','操作时间必须为时间格式',self::EXISTS_VALIDATE,'function'),//存在即验证，必须为时间格式		
	);
	
	/**
	 * 获取关列表
	 * @param int $operation_id:作业ID
	 * @return array|boolean
	 */
	public function getLevelList($operation_id)
	{
		$sql="select l.*,u.user_name from __PREFIX__dd_operation_level l,__PREFIX__user u where l.operation_id=$operation_id and l.operator_id=u.uid order by l.level_num asc";
		$list=M()->query($sql);
		if($list!==false)
		{
			//关残损照片
			$num=count($list);
			$DdCargoDamageImg=new \Common\Model\DdCargoDamageImgModel();
			for ($i=0;$i<$num;$i++)
			{
				//根据关ID获取关的货残损照片
				$level_id=$list[$i]['id'];
				$cargo_damage_img_list=$DdCargoDamageImg->where("level_id=$level_id")->select();
				if($cargo_damage_img_list!==false)
				{
					$list[$i]['cargo_damage_img']=$cargo_damage_img_list;
				}else      {
					$list[$i]['cargo_damage_img']='';
				}
				$cargo_damage_img_list='';
				$level_id='';
			}
			// 关货物照片
			$DdLevelCargoImg = new \Common\Model\DdLevelCargoImgModel();
			for ($i=0;$i<$num;$i++)
			{
				//根据关ID获取关的货照片
				$level_id=$list[$i]['id'];
				$level_cargo_img_list = $DdLevelCargoImg->where("level_id=$level_id")->select();
				if($level_cargo_img_list!==false)
				{
					$list[$i]['cargo_level_img']=$level_cargo_img_list;
				}else      {
					$list[$i]['cargo_level_img']='';
				}
				$level_cargo_img_list='';
				$level_id='';
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
		$num=$this->where("operation_id='$operation_id'")->sum('num');
		return $num;
	}
	
	/**
	 * 根据作业ID计算残损件数
	 * @param int $operation_id:作业ID
	 * @return number
	 */
	public function sumDamageNum($operation_id)
	{
		$num=$this->where("operation_id='$operation_id'")->sum('damage_num');
		return $num;
	}
}
?>