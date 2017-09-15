<?php
/**
 * 门到门拆箱业务类
 * 作业管理类
 */
namespace Common\Model;
use Think\Model;

class DdOperationModel extends Model
{
	//验证规则
	protected $_validate = array(
		array('ctn_id','require','箱不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('ctn_id','is_positive_int','箱不存在',self::EXISTS_VALIDATE,'function'),  //存在即验证，必须为正整数
		array('door_picture','1,255','箱门照片路径不正确',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
		array('seal_picture','1,255','铅封照片路径不正确',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
		array('damage_remark','1,200','箱残损备注不超过200个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过200个字符
		array('true_sealno','1,20','实际铅封号不超过20个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过20个字符
		array('cargo_picture','1,255','整箱货物照片路径不正确',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
		array('empty_picture','1,255','空箱照片路径不正确',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
		array('damaged_after_remark','1,200','作业中箱残损备注不超过200个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过200个字符
		array('operator_id','require','理货员不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('operator_id','is_positive_int','理货员不存在',self::EXISTS_VALIDATE,'function'), //存在即验证 ，必须为正整数
		array('begin_time','require','作业开始时间不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('begin_time','is_datetime','作业开始时间必须为时间格式',self::EXISTS_VALIDATE,'function'),  //存在即验证，必须我时间格式
		array('is_stop','require','请选择是否暂停作业',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('is_stop',array('Y','N'),'请选择是否暂停作业',self::EXISTS_VALIDATE,'in'), //存在即验证，是Y否N
		array('step','require','工作步骤不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
		array('step',array(0,9),'该工作步骤不存在',self::EXISTS_VALIDATE,'between'), //存在即验证，必须在0-9范围内
	);
	
	/**
	 * 根据箱ID获取作业详情
	 * @param int $ctn_id:箱ID
	 * @return array|boolean
	 */
	public function getOperationMsgByCtn($ctn_id)
	{
		$msg=$this->where("ctn_id=$ctn_id")->find();
		if($msg!==false)
		{
			if($msg['id']!='')
			{
				$operation_id=$msg['id'];
				//根据作业ID获取作业前箱残损照片
				$ctn_damage = new \Common\Model\DdCtnDamageImgModel();
				$ctn_damage_img=$ctn_damage->where("operation_id=$operation_id")->select();
				$msg['ctn_damage_img']=$ctn_damage_img;
				//根据作业ID获取作业后箱残损照片
				$ctn_damage_after = new \Common\Model\DdCtnDamageAfterImgModel();
				$ctn_damage_after_img=$ctn_damage_after->where("operation_id=$operation_id")->select();
				$msg['ctn_damage_after_img']=$ctn_damage_after_img;
				//根据作业ID获取补充照片
				$supplement = new \Common\Model\DdSupplementPictureModel();
				$supplement_picture=$supplement->where("operation_id='$operation_id'")->select();
				$msg['supplement_picture']= $supplement_picture;
			}
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取作业详情
	 * @param int $id:作业ID
	 * @return array|boolean
	 */
	public function getOperationMsg($id)
	{
		$msg=$this->where("id=$id")->find();
		if($msg!==false)
		{
			//根据作业ID获取作业前箱残损照片
			$ctn_damage = new \Common\Model\DdCtnDamageImgModel();
			$ctn_damage_img=$ctn_damage->where("operation_id=$id")->select();
			$msg['ctn_damage_img']=$ctn_damage_img;
			//根据作业ID获取作业后箱残损照片
			$ctn_damage_after = new \Common\Model\DdCtnDamageAfterImgModel();
			$ctn_damage_after_img=$ctn_damage_after->where("operation_id=$id")->select();
			$msg['ctn_damage_after_img']=$ctn_damage_after_img;
			return $msg;
		}else {
			return false;
		}
	}

	// 获取相应状态的箱列表-工作中、已完成
	public function get_num($instruction_id,$status,$uid)
	{
		// 获取相应状态的箱列表-工作中、已完成
		$sql3 = "select c.*,i.is_must,unpackagingplace from __PREFIX__dd_instruction i,__PREFIX__dd_plan_container c,__PREFIX__dd_plan p where i.plan_id=c.plan_id and i.id in ($instruction_id)  and c.status = '$status' and c.operator_id=$uid and p.id=c.plan_id order by i.is_must desc,c.id desc";
		$list = M ()->query ( $sql3 );
		if ($list !== false) {
			$cnum = count ( $list );
			$res=array(
					'code'=>0,
					'msg'=>'成功',
					'num'=>$cnum
				);
		}else{
			$res=array(
					'code'=>3,
					'msg'=>'失败'
				);
		}
		return $res;
	}
}
?>