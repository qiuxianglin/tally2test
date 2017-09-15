<?php
/**
 * 起驳装箱业务类
 * 作业关管理
 */

namespace Common\Model;
use Think\Model;

class QbzxOperationLevelModel extends Model
{
	
	//验证规则
	protected $_validate = array(
		array('operation_id','require','作业不能为空',self::EXISTS_VALIDATE), //存在验证，不能为空		
		array('operation_id','is_positive_int','作业不存在',self::EXISTS_VALIDATE,'function'), //存在验证，必须为正整数
		array('level_num','require','关数不能为空',self::EXISTS_VALIDATE),//存在验证，不能为空
		array('level_num','is_positive_int','关数必须为正整数',self::EXISTS_VALIDATE,'function'), //存在即验证，必须为正整数
		array('cargo_number','require','货物重量不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('cargo_number','number','货物重量必须为数字',self::EXISTS_VALIDATE), // 存在即验证，必须为数字
		array('level_picture','require','关照片不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('level_picture','1,255','关照长度不能超过255个字符',self::EXISTS_VALIDATE,'length'), //值不为空验证，长度不能超过255个字符
		array('damage_num','number','残损数量必须为数字',self::VALUE_VALIDATE),//值不为空即验证，必须为数字
		array('damage_explain','1,255','长度不能超过255个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证，长度不能超过255个字符
		array('billno','require','提单号不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('billno','1,20','提单号长度不能超过20个字符',self::EXISTS_VALIDATE,'length'),//存在即验证，长度不能超过20个字符
		array('ship_id','is_positive_int','船舶不存在',self::VALUE_VALIDATE,'function'), //值不为空即验证，必须为正整数
		array('location_id','is_positive_int','作业地点不存在',self::VALUE_VALIDATE,'function'), //值不为空即验证，必须为正整数	
		array('car','1,20','车牌号不能超过20个字符',self::VALUE_VALIDATE,'length'), //值不为空验证，长度不能超过20个字符
		array('comment','1,255','长度不能超过255个字符',self::VALUE_VALIDATE,'length'),//值不为空即验证，长度不能超过255个字符
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
		$sql = "select l.*,u.user_name,p.port_id,p.pack,p.mark from __PREFIX__qbzx_operation_level l,__PREFIX__user u,__PREFIX__qbzx_plan_cargo p where l.operation_id='$operation_id' and l.operator_id=u.uid and p.billno=l.billno";
		$list = M ()->query ( $sql );
		if ($list !== false) 
		{
			$num = count ( $list );
			$cargo_damage_img = new \Common\Model\QbzxLevelDamageImgModel ();
			$cargo_img = new \Common\Model\QbzxLevelCargoImgModel();
			for($i = 0; $i < $num; $i ++) 
			{
				//获取货物照片完整地址
// 				$list[$i]['cargo_picture_a'] = IMAGE_QBZX_CARGO.$list[$i]['cargo_picture'];
				// 根据作业ID和关序号获取关的货残损照片
				// 关序号
				$sling = $list [$i] ['level_num'];
				$level_id = $list [$i] ['id'];
				$cargo_damage_img_list = $cargo_damage_img->where ( "level_num='$sling' and level_id='$level_id'" )->select ();
				if ($cargo_damage_img_list !== false) 
				{
					//拼接残损照片路径
					foreach($cargo_damage_img_list as $key => $vo)
					{
						$cargo_damage_img_list[$key]['damage_picture'] = IMAGE_QBZX_CDAMAGE.$cargo_damage_img_list[$key]['damage_picture'];
					}
					$list [$i] ['damage_picture'] = $cargo_damage_img_list;
				} else {
					$list [$i] ['damage_picture'] = '';
				}
				$cargo_damage_img_list = '';
				// 根据作业ID和关序号获取关的货照片
				$cargo_img_list = $cargo_img->where ( "level_num='$sling' and level_id='$level_id'" )->select ();
				if ($cargo_img_list !== false)
				{
					//拼接货物照片路径
					foreach($cargo_img_list as $key => $vo)
					{
						$cargo_img_list[$key]['cargo_picture'] = IMAGE_QBZX_CARGO.$cargo_img_list[$key]['cargo_picture'];
					}
					$list [$i] ['cargo_picture'] = $cargo_img_list;
				} else {
					$list [$i] ['cargo_picture'] = '';
				}
				$cargo_img_list = '';
				$sling = '';
				//根据port_id 获取目的港名称
				$port_id = $list[$i]['port_id'];
				$port = new \Common\Model\PortModel();
				$port_name = $port->field('name')->where("id='$port_id'")->find();
				$list[$i]['poname'] = $port_name['name'];
				//根据ship_id 获取驳船名称
				$ship_id =  $list[$i]['ship_id'];
				$ship = new \Common\Model\ShipModel();
				$ship_name = $ship->where("id='$ship_id'")->field('ship_name')->find();
				$list[$i]['ship_name'] = $ship_name['ship_name'];
			}
			return $list;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取关详情
	 * 
	 * @param int $level_id:关ID        	
	 * @return array|boolean
	 */
	public function getLevelMsg($level_id) 
	{
		$msg = $this->where ( "id=$level_id" )->find ();
		if ($msg !== false) 
		{
			return $msg;
		} else {
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
		$num=$this->where("operation_id=$operation_id")->count();
		return $num;
	}
	
	/**
	 * 根据作业ID计算货物件数
	 * @param int $operation_id:作业ID
	 * @return number
	 */
	public function sumCargoNum($operation_id)
	{
		$num=$this->where("operation_id=$operation_id")->sum('cargo_number');
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