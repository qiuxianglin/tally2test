<?php
/**
 * CFS装箱业务类
 * 作业管理类
 */
namespace Common\Model;
use Think\Model;

class CfsOperationModel extends Model
{
	//验证规则
	protected $_validate = array(
			array('ctn_id','require','箱不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('ctn_id','is_positive_int','箱ID必须为正整数',self::EXISTS_VALIDATE,'function'),  //值不为空即验证，必须为正整数
			array('empty_weight','number','空箱重量必须为数字',self::VALUE_VALIDATE), //值不为空即验证，必须为数字
			array('halfclosedoor_picture','1,255','半关门照长度不能超过255个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
			array('closedoor_picture','1,255','全关门照长度不能超过255个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
			array('tmp_sealno','1,20','临时铅封号长度不能超过20个字符',self::VALUE_VALIDATE,'length'),  //值不为空验证，长度不能超过20个字符
			array('sealno','1,20','铅封号长度不能超过20个字符',self::VALUE_VALIDATE,'length'),  //值不为空验证，长度不能超过20个字符
			array('seal_picture','1,255','铅封照长度不能超过255个字符',self::VALUE_VALIDATE,'length'),  //值不为空即验证，长度不能超过255个字符
			array('cargo_weight','number','货物重量必须为数字',self::VALUE_VALIDATE), //值不为空即验证，必须为数字
			array('operation_id','is_positive_int','作业不存在',self::VALUE_VALIDATE,'function'), //值不为空的时候验证 ，必须为正整数
			array('begin_time','require','开始时间不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('begin_time','is_datetime','开始时间必须为时间格式',self::EXISTS_VALIDATE,'function'),  //存在即验证，必须我时间格式
			array('is_stop','require','暂停作业不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('is_stop',array('Y','N'),'请选择是否暂停作业',self::EXISTS_VALIDATE,'in'), //存在即验证，是Y否N
			array('is_reservation','require','是否预约不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('is_reservation',array('Y','N'),'请选择是否预约',self::EXISTS_VALIDATE,'in'), //存在即验证，是Y否N
			array('step','require','步骤不能为空',self::EXISTS_VALIDATE),//存在即验证，不能为空
			array('step','number','步骤必须为数字',self::EXISTS_VALIDATE), //存在即验证,必须为数字
			array('step',array(1,9),'步骤必须在1-9范围内',self::EXISTS_VALIDATE,'between'), //存在即验证，必须在1-9范围内
	);
	/**
	 * 根据箱ID获取作业详情
	 * @param int $ctn_id:箱ID
	 * @return array|boolean
	 */
	public function getOperationMsgByCtn($ctn_id)
	{
		$msg=$this->where("ctn_id=$ctn_id")->find();
		// $where = "operation_id = ".$msg['id'];
		// $empty_img = M('tally_cfs_ctn_empty_img')->where($where)->select();
		$sql = "select empty_img from tally_cfs_ctn_empty_img where operation_id='".$msg['id']."'";
		$empty_img = M ()->query ( $sql );
		$msg['empty_img'] = $empty_img;

		if($msg!==false)
		{
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
			return $msg;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取作业详情
	 * @param int $ctn_id:箱ID
	 * @return array|boolean
	 */
	public function getOperation($ctn_id)
	{
		$msg=$this->where("ctn_id='$ctn_id'")->find();
		if($msg!==false)
		{
			return $msg;
		}else {
			return false;
		}
	}

	// 获取相应状态的箱列表-工作中、已完成
	public function get_num($instruction_id,$status,$uid)
	{
		// 获取相应状态的箱列表-工作中、已完成
		$sql3 = "select c.* from __PREFIX__cfs_instruction i,__PREFIX__cfs_instruction_ctn c where i.id=c.instruction_id and i.id in ($instruction_id)  and c.status='$status' and c.operator_id='$uid' order by c.id desc";
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